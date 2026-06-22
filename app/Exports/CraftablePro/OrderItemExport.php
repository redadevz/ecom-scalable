<?php

namespace App\Exports\CraftablePro;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\OrderItem;

class OrderItemExport implements FromCollection, WithHeadings
{
    protected mixed $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        return QueryBuilder::for(OrderItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'order_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'created_at'
                )),
            ])
            ->defaultSort('id')
            ->allowedSorts('id', 'order_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'created_at')
            ->select(['id', 'order_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'created_at'])
            ->get();
    }

    public function headings(): array
    {
        return [
            ___('craftable-pro', 'Id'),
            ___('craftable-pro', 'Order Id'),
            ___('craftable-pro', 'Product Id'),
            ___('craftable-pro', 'Product Name'),
            ___('craftable-pro', 'Quantity'),
            ___('craftable-pro', 'Unit Price'),
            ___('craftable-pro', 'Created At')
        ];
    }
}
