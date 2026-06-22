<?php

namespace App\Exports\CraftablePro;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Product;

class ProductExport implements FromCollection, WithHeadings
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
        return QueryBuilder::for(Product::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'name', 'slug', 'description', 'price', 'stock', 'category', 'image_url', 'created_at'
                )),
            ])
            ->defaultSort('id')
            ->allowedSorts('id', 'name', 'slug', 'description', 'price', 'stock', 'category', 'image_url', 'created_at')
            ->select(['id', 'name', 'slug', 'description', 'price', 'stock', 'category', 'image_url', 'created_at'])
            ->get();
    }

    public function headings(): array
    {
        return [
            ___('craftable-pro', 'Id'),
            ___('craftable-pro', 'Name'),
            ___('craftable-pro', 'Slug'),
            ___('craftable-pro', 'Description'),
            ___('craftable-pro', 'Price'),
            ___('craftable-pro', 'Stock'),
            ___('craftable-pro', 'Category'),
            ___('craftable-pro', 'Image Url'),
            ___('craftable-pro', 'Created At')
        ];
    }
}
