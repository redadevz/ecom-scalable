<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TillSession extends Model
{
    protected $fillable = [
        'store_id', 'opened_by', 'closed_by', 'opening_float',
        'cash_sales', 'other_sales', 'expected_cash', 'counted_cash',
        'variance', 'status', 'notes', 'opened_at', 'closed_at',
    ];

    protected $casts = [
        'opening_float' => 'decimal:3',
        'cash_sales'    => 'decimal:3',
        'other_sales'   => 'decimal:3',
        'expected_cash' => 'decimal:3',
        'counted_cash'  => 'decimal:3',
        'variance'      => 'decimal:3',
        'opened_at'     => 'datetime',
        'closed_at'     => 'datetime',
    ];

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
