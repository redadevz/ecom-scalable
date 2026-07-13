<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $approved = OrderHeader::where('is_approved', true)->where('is_canceled', false);

        $recentOrders = OrderHeader::with('customer:id,first_name,last_name,company_name,is_company')
            ->where('is_approved', true)
            ->latest('id')
            ->take(6)
            ->get()
            ->map(fn ($o) => [
                'order_no' => $o->order_no,
                'customer' => $this->customerName($o->customer),
                'total'    => (float) $o->price,
                'status'   => $o->latest_status,
                'is_paid'  => (bool) $o->is_paid,
            ]);

        $topItems = OrderLine::query()
            ->selectRaw('item_id, SUM(quantity) as qty')
            ->whereHas('order', fn ($q) => $q->where('is_approved', true))
            ->groupBy('item_id')
            ->orderByDesc('qty')
            ->take(5)
            ->with('item:id,name,sku_code')
            ->get()
            ->map(fn ($l) => [
                'name' => $l->item?->name ?? '—',
                'sku'  => $l->item?->sku_code,
                'qty'  => (int) $l->qty,
            ]);

        // Monthly sales + order count for the last 12 months (Performance chart)
        $monthly = collect(range(11, 0))->map(function ($back) {
            $month = Carbon::now()->startOfMonth()->subMonths($back);
            $scope = OrderHeader::where('is_approved', true)->where('is_canceled', false)
                ->whereBetween('created_at', [$month->copy(), $month->copy()->endOfMonth()]);
            return [
                'label'  => $month->format('M'),
                'value'  => round((float) (clone $scope)->sum('price'), 2),
                'orders' => (clone $scope)->count(),
            ];
        })->values();

        // this-month vs last-month trend for sales & orders
        $tm = Carbon::now()->startOfMonth();
        $lm = Carbon::now()->startOfMonth()->subMonth();
        $salesThis = (float) OrderHeader::where('is_approved', true)->where('is_canceled', false)->where('created_at', '>=', $tm)->sum('price');
        $salesLast = (float) OrderHeader::where('is_approved', true)->where('is_canceled', false)->whereBetween('created_at', [$lm, $tm])->sum('price');
        $ordThis = OrderHeader::where('is_approved', true)->where('is_canceled', false)->where('created_at', '>=', $tm)->count();
        $ordLast = OrderHeader::where('is_approved', true)->where('is_canceled', false)->whereBetween('created_at', [$lm, $tm])->count();

        return Inertia::render('Home', [
            'stats' => [
                'sales_total'     => round((float) (clone $approved)->sum('price'), 3),
                'orders_count'    => (clone $approved)->count(),
                'low_stock_count' => Item::whereColumn('current_stock_quantity', '<=', 'low_stock_quantity')
                    ->where('is_service', false)->where('is_active', true)->count(),
                'unpaid_invoices' => Invoice::where('is_paid', false)->count(),
                'sales_trend'     => $this->trend($salesThis, $salesLast),
                'orders_trend'    => $this->trend((float) $ordThis, (float) $ordLast),
            ],
            'monthly'      => $monthly,
            'recentOrders' => $recentOrders,
            'topItems'     => $topItems,
        ]);
    }

    private function trend(float $now, float $prev): float
    {
        if ($prev <= 0) {
            return $now > 0 ? 100.0 : 0.0;
        }
        return round((($now - $prev) / $prev) * 100, 1);
    }
    
    private function customerName($c): string
    {
        if (! $c) {
            return '—';
        }
        return trim($c->is_company ? (string) $c->company_name : "{$c->first_name} {$c->last_name}") ?: '—';
    }
}
