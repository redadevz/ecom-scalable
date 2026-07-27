<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderHeader;
use App\Services\OrderService;
use App\Settings\ShopSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    public function index(ShopSettings $settings): Response
    {
        $customer = Auth::guard('customer')->user();

        $orders = OrderHeader::where('customer_id', $customer->id)
            ->latest('id')
            ->take(50)
            ->get()
            ->map(fn (OrderHeader $o) => [
                'id'       => $o->id,
                'order_no' => $o->order_no,
                'date'     => $o->created_at?->format('d M Y'),
                'total'    => (float) $o->price,
                'status'   => $o->latest_status,
                'is_paid'  => (bool) $o->is_paid,
            ]);

        return Inertia::render('Shop/Account/Index', [
            'customer' => [
                'first_name'      => $customer->first_name,
                'last_name'       => $customer->last_name,
                'email'           => $customer->email,
                'phone'           => $customer->phone,
                'billing_address' => $customer->billing_address,
                'postal_code'     => $customer->postal_code,
            ],
            'orders'   => $orders,
            'currency' => $settings->currency_symbol,
        ]);
    }

    /** A single order with its fulfilment timeline — customer-facing tracking. */
    public function order(OrderHeader $orderHeader, ShopSettings $settings): Response
    {
        $customer = Auth::guard('customer')->user();
        abort_unless($orderHeader->customer_id === $customer->id, 404);

        $orderHeader->load([
            'orderLines.item',
            'orderStatusHistories.orderStatus',
        ]);

        // completed timeline entries, oldest first
        $history = $orderHeader->orderStatusHistories
            ->sortBy('start_time')
            ->map(fn ($h) => [
                'status' => $h->orderStatus?->name,
                'at'     => $h->start_time?->format('d M Y, H:i'),
            ])
            ->values();

        $pipeline = OrderService::FULFILMENT; // Approved → Ready → Completed
        $current  = $orderHeader->latest_status;
        $reached  = $history->pluck('status')->all();

        $steps = collect($pipeline)->map(fn ($name) => [
            'name'    => $name,
            'label'   => $this->stepLabel($name),
            'done'    => in_array($name, $reached, true),
            'current' => $name === $current,
            'at'      => optional($history->firstWhere('status', $name))['at'],
        ]);

        return Inertia::render('Shop/Account/Order', [
            'order' => [
                'order_no'   => $orderHeader->order_no,
                'date'       => $orderHeader->created_at?->format('d M Y'),
                'total'      => (float) $orderHeader->price,
                'is_paid'    => (bool) $orderHeader->is_paid,
                'canceled'   => (bool) $orderHeader->is_canceled,
                'status'     => $current,
                'lines'      => $orderHeader->orderLines->map(fn ($l) => [
                    'name'  => $l->item?->name ?? '—',
                    'qty'   => (int) $l->quantity,
                    'total' => (float) $l->price,
                ])->values(),
            ],
            'steps'    => $steps,
            'currency' => $settings->currency_symbol,
        ]);
    }

    private function stepLabel(string $status): string
    {
        return [
            'Approved'  => 'Order confirmed',
            'Ready'     => 'Ready for pickup',
            'Completed' => 'Completed',
        ][$status] ?? $status;
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $customer = Auth::guard('customer')->user();

        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:50'],
            'last_name'       => ['required', 'string', 'max:50'],
            'phone'           => ['required', 'string', 'max:50'],
            'billing_address' => ['nullable', 'string', 'max:255'],
            'postal_code'     => ['nullable', 'string', 'max:20'],
        ]);

        $customer->update($data);

        return back()->with('message', 'Profile updated.');
    }
}
