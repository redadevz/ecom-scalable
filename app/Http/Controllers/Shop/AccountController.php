<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderHeader;
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
