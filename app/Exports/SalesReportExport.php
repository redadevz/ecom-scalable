<?php

namespace App\Exports;

use App\Models\OrderHeader;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected Carbon $from,
        protected Carbon $to,
    ) {}

    public function collection(): Collection
    {
        return OrderHeader::query()
            ->with('customer:id,first_name,last_name,company_name,is_company')
            ->where('is_approved', true)
            ->where('is_canceled', false)
            ->whereBetween('created_at', [$this->from, $this->to])
            ->latest('created_at')
            ->get();
    }

    public function headings(): array
    {
        return ['Order No', 'Date', 'Customer', 'Paid', 'Total'];
    }

    public function map($order): array
    {
        $c = $order->customer;
        $name = $c
            ? trim($c->is_company ? (string) $c->company_name : "{$c->first_name} {$c->last_name}")
            : '';

        return [
            $order->order_no,
            optional($order->created_at)->format('Y-m-d'),
            $name ?: '—',
            $order->is_paid ? 'Paid' : 'Unpaid',
            (float) $order->price,
        ];
    }
}
