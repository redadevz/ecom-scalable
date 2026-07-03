<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Models\OrderHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

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

    private function name($c): string
    {
        if (! $c) {
            return '—';
        }
        return trim($c->is_company ? (string) $c->company_name : "{$c->first_name} {$c->last_name}") ?: '—';
    }
}
