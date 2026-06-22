<?php

namespace App\Exports\CraftablePro;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Brackets\CraftablePro\Queries\Filters\FuzzyFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Payment;

class PaymentExport implements FromCollection, WithHeadings
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
        return QueryBuilder::for(Payment::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'id', 'order_id', 'provider', 'provider_reference', 'status', 'amount', 'currency', 'created_at'
                )),
            ])
            ->defaultSort('id')
            ->allowedSorts('id', 'order_id', 'provider', 'provider_reference', 'status', 'amount', 'currency', 'created_at')
            ->select(['id', 'order_id', 'provider', 'provider_reference', 'status', 'amount', 'currency', 'created_at'])
            ->get();
    }

    public function headings(): array
    {
        return [
            ___('craftable-pro', 'Id'),
            ___('craftable-pro', 'Order Id'),
            ___('craftable-pro', 'Provider'),
            ___('craftable-pro', 'Provider Reference'),
            ___('craftable-pro', 'Status'),
            ___('craftable-pro', 'Amount'),
            ___('craftable-pro', 'Currency'),
            ___('craftable-pro', 'Created At')
        ];
    }
}
