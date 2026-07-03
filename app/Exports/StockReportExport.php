<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Item::query()
            ->where('is_service', false)
            ->with(['itemCategory:id,name', 'prices' => fn ($q) => $q->where('is_active', true)->latest('id')])
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return ['Item', 'SKU', 'Category', 'Stock', 'Low Stock Alert', 'Unit Cost', 'Stock Value'];
    }

    public function map($item): array
    {
        $cost = (float) optional($item->prices->first())->current_item_cost;
        $qty  = (float) $item->current_stock_quantity;

        return [
            $item->name,
            $item->sku_code,
            $item->itemCategory?->name ?? '—',
            $qty,
            (float) $item->low_stock_quantity,
            $cost,
            round($qty * $cost, 2),
        ];
    }
}
