<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\OrderHeader;
use App\Models\OrderLine;
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

        return Inertia::render('Home', [
            'stats' => [
                'sales_total'     => round((float) (clone $approved)->sum('price'), 3),
                'orders_count'    => (clone $approved)->count(),
                'low_stock_count' => Item::whereColumn('current_stock_quantity', '<=', 'low_stock_quantity')
                    ->where('is_service', false)->where('is_active', true)->count(),
                'unpaid_invoices' => Invoice::where('is_paid', false)->count(),
            ],
            'recentOrders' => $recentOrders,
            'topItems'     => $topItems,
        ]);
    }
    
    private function customerName($c): string
    {
        if (! $c) {
            return '—';
        }
        return trim($c->is_company ? (string) $c->company_name : "{$c->first_name} {$c->last_name}") ?: '—';
    }
}
