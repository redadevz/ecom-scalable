<?php

namespace App\Exports\CraftablePro;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\CartItem;

class CartItemExport implements FromCollection, WithHeadings
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
        return QueryBuilder::for(CartItem::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'cart_id', 'product_id', 'quantity', 'unit_price', 'created_at'
                )),
            ])
            ->defaultSort('id')
            ->allowedSorts('id', 'cart_id', 'product_id', 'quantity', 'unit_price', 'created_at')
            ->select(['id', 'cart_id', 'product_id', 'quantity', 'unit_price', 'created_at'])
            ->get();
    }

    public function headings(): array
    {
        return [
            ___('craftable-pro', 'Id'),
            ___('craftable-pro', 'Cart Id'),
            ___('craftable-pro', 'Product Id'),
            ___('craftable-pro', 'Quantity'),
            ___('craftable-pro', 'Unit Price'),
            ___('craftable-pro', 'Created At')
        ];
    }
}
