<?php

namespace App\Http\Controllers\CraftablePro;

use App\Exports\PurchasesReportExport;
use App\Exports\SalesReportExport;
use App\Exports\StockReportExport;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\OrderHeader;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{

    public function sales(Request $request): Response
    {
        $from = ($request->date('from') ?: Carbon::now()->subDays(30))->startOfDay();
        $to   = ($request->date('to') ?: Carbon::now())->endOfDay();

        $orders = OrderHeader::query()
            ->with('customer:id,first_name,last_name,company_name,is_company')
            ->where('is_approved', true)
            ->where('is_canceled', false)
            ->whereBetween('created_at', [$from, $to])
            ->latest('created_at')
            ->get()
            ->map(fn ($o) => [
                'order_no' => $o->order_no,
                'date'     => $o->created_at?->format('Y-m-d'),
                'customer' => $this->name($o->customer),
                'total'    => (float) $o->price,
                'is_paid'  => (bool) $o->is_paid,
            ]);

        return Inertia::render('Reports/Sales', [
            'filters' => ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')],
            'summary' => [
                'total'   => round((float) $orders->sum('total'), 2),
                'count'   => $orders->count(),
                'average' => $orders->count() ? round((float) $orders->avg('total'), 2) : 0,
                'paid'    => round((float) $orders->where('is_paid', true)->sum('total'), 2),
            ],
            'orders' => $orders->values(),
        ]);
    }

    /**
     * Stock report —    levels, low-stock flags, inventory valuation.
     */
    public function stock(): Response
    {
        $items = Item::query()
            ->where('is_service', false)
            ->with(['itemCategory:id,name', 'prices' => fn ($q) => $q->where('is_active', true)->latest('id')])
            ->orderBy('name')
            ->get()
            ->map(function ($it) {
                $cost = (float) optional($it->prices->first())->current_item_cost;
                $qty  = (float) $it->current_stock_quantity;

                return [
                    'name'      => $it->name,
                    'sku'       => $it->sku_code,
                    'category'  => $it->itemCategory?->name ?? '—',
                    'stock'     => $qty,    
                    'low_stock' => (float) $it->low_stock_quantity,
                    'is_low'    => $qty <= (float) $it->low_stock_quantity,
                    'cost'      => $cost,
                    'value'     => round($qty * $cost, 2),
                ];
            });

        return Inertia::render('Reports/Stock', [
            'summary' => [
                'items'     => $items->count(),
                'low_stock' => $items->where('is_low', true)->count(),
                'units'     => (int) $items->sum('stock'),
                'value'     => round((float) $items->sum('value'), 2),
            ],
            'items' => $items->values(),
        ]);
    }

    public function stockExport(): BinaryFileResponse
    {
        return Excel::download(new StockReportExport(), 'stock-report.xlsx');
    }

    /**
     * Purchases report — spend grouped by supplier.
     */
    public function purchases(): Response
    {
        $purchases = Purchase::query()
            ->with(['supplier:id,company_name,code', 'purchaseItems:id,purchase_id,quantity,supplier_price_after_tax'])
            ->get();

        $rows = $purchases
            ->groupBy('supplier_id')
            ->map(function ($group) {
                $supplier = $group->first()->supplier;
                $spend = $group->sum(fn ($p) => $p->purchaseItems->sum(
                    fn ($i) => (float) $i->supplier_price_after_tax * (float) $i->quantity
                ));

                return [
                    'supplier'  => $supplier ? ($supplier->company_name ?: $supplier->code) : '—',
                    'purchases' => $group->count(),
                    'received'  => $group->whereNotNull('entry_stock_time')->count(),
                    'spend'     => round($spend, 2),
                ];
            })
            ->sortByDesc('spend')
            ->values();

        return Inertia::render('Reports/Purchases', [
            'summary' => [
                'suppliers' => $rows->count(),
                'purchases' => $purchases->count(),
                'spend'     => round((float) $rows->sum('spend'), 2),
            ],
            'rows' => $rows,
        ]);
    }

    public function purchasesExport(): BinaryFileResponse
    {
        return Excel::download(new PurchasesReportExport(), 'purchases-report.xlsx');
    }

    /**
     * Download the sales report (same filter) as an Excel file.
     */
    public function salesExport(Request $request): BinaryFileResponse
    {
        $from = ($request->date('from') ?: Carbon::now()->subDays(30))->startOfDay();
        $to   = ($request->date('to') ?: Carbon::now())->endOfDay();

        return Excel::download(new SalesReportExport($from, $to), 'sales-report.xlsx');
    }

    private function name($c): string
    {
        if (! $c) {
            return '—';
        }
        return trim($c->is_company ? (string) $c->company_name : "{$c->first_name} {$c->last_name}") ?: '—';
    }
}
