<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ShopSettings extends Settings
{
    public string $currency_code;       

    public string $currency_symbol;     

    public float $default_tax_rate;     

    public bool $negative_stock_allowed;

    public int $low_stock_threshold;

    public static function group(): string
    {
        return 'shop';
    }
}
