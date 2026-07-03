<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesReportExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return Purchase::query()
            ->with(['supplier:id,company_name,code', 'purchaseItems:id,purchase_id,quantity,supplier_price_after_tax'])
            ->get()
            ->groupBy('supplier_id')
            ->map(function ($group) {
                $supplier = $group->first()->supplier;
                $spend = $group->sum(fn ($p) => $p->purchaseItems->sum(
                    fn ($i) => (float) $i->supplier_price_after_tax * (float) $i->quantity
                ));

                return [
                    $supplier ? ($supplier->company_name ?: $supplier->code) : '—',
                    $group->count(),
                    $group->whereNotNull('entry_stock_time')->count(),
                    round($spend, 2),
                ];
            })
            ->values()
            ->toArray();
    }

    public function headings(): array
    {
        return ['Supplier', 'Purchases', 'Received', 'Total Spend'];
    }
}
