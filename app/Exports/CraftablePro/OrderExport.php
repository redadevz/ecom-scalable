<?php

namespace App\Exports\CraftablePro;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Order;

class OrderExport implements FromCollection, WithHeadings
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
        return QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'user_id', 'status', 'total', 'created_at'
                )),
            ])
            ->defaultSort('id')
            ->allowedSorts('id', 'user_id', 'status', 'total', 'created_at')
            ->select(['id', 'user_id', 'status', 'total', 'created_at'])
            ->get();
    }

    public function headings(): array
    {
        return [
            ___('craftable-pro', 'Id'),
            ___('craftable-pro', 'User Id'),
            ___('craftable-pro', 'Status'),
            ___('craftable-pro', 'Total'),
            ___('craftable-pro', 'Created At')
        ];
    }
}
