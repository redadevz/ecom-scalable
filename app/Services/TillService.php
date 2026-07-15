<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Store;
use App\Models\TillSession;
use Illuminate\Support\Facades\DB;
use RuntimeException;


class TillService
{
    /** The currently open session for the (default) store, if any. */
    public function current(?int $storeId = null): ?TillSession
    {
        $storeId ??= Store::orderBy('id')->value('id');

        return TillSession::where('status', 'open')
            ->where('store_id', $storeId)
            ->latest('id')
            ->first();
    }

    public function open(int $storeId, ?int $userId, float $openingFloat): TillSession
    {
        if ($this->current($storeId)) {
            throw new RuntimeException('A register session is already open.');
        }

        return TillSession::create([
            'store_id'      => $storeId,
            'opened_by'     => $userId,
            'opening_float' => $openingFloat,
            'status'        => 'open',
            'opened_at'     => now(),
        ]);
    }

    
    public function summary(TillSession $session): array
    {
        $to = $session->closed_at ?? now();

        $cashSales  = $this->salesTotal($session, $to, cashOnly: true);
        $otherSales = $this->salesTotal($session, $to, cashOnly: false) - $cashSales;
        $expected   = round((float) $session->opening_float + $cashSales, 3);

        return [
            'opening_float' => (float) $session->opening_float,
            'cash_sales'    => round($cashSales, 3),
            'other_sales'   => round($otherSales, 3),
            'total_sales'   => round($cashSales + $otherSales, 3),
            'expected_cash' => $expected,
        ];
    }

    public function close(TillSession $session, float $countedCash, ?string $notes = null): TillSession
    {
        if (! $session->isOpen()) {
            throw new RuntimeException('This register session is already closed.');
        }

        return DB::transaction(function () use ($session, $countedCash, $notes) {
            $s = $this->summary($session);

            $session->update([
                'closed_by'     => auth('craftable-pro')->id(),
                'cash_sales'    => $s['cash_sales'],
                'other_sales'   => $s['other_sales'],
                'expected_cash' => $s['expected_cash'],
                'counted_cash'  => round($countedCash, 3),
                'variance'      => round($countedCash - $s['expected_cash'], 3),
                'status'        => 'closed',
                'notes'         => $notes,
                'closed_at'     => now(),
            ]);

            return $session->fresh();
        });
    }

    /** Sum of payments taken during the session window, optionally cash only. */
    private function salesTotal(TillSession $session, $to, bool $cashOnly): float
    {
        $query = Payment::query()
            ->where('payment_time', '>=', $session->opened_at)
            ->where('payment_time', '<=', $to);

        if ($cashOnly) {
            $cashIds = PaymentMethod::where('name', 'Cash')->pluck('id');
            if ($cashIds->isEmpty()) {
                return 0.0; // no cash method configured
            }
            $query->whereIn('payment_method_id', $cashIds);
        }

        return (float) $query->sum('amount');
    }
}
