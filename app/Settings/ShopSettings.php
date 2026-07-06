<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ShopSettings extends Settings
{
    public string $currency_code;       // e.g. "MAD"

    public string $currency_symbol;     // e.g. "DH"

    public float $default_tax_rate;     // percent, e.g. 20

    public bool $negative_stock_allowed;

    public int $low_stock_threshold;

    public static function group(): string
    {
        return 'shop';
    }
}
