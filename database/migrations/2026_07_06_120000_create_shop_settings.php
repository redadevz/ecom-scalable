<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateShopSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shop.currency_code', 'MAD');
        $this->migrator->add('shop.currency_symbol', 'DH');
        $this->migrator->add('shop.default_tax_rate', 20.0);
        $this->migrator->add('shop.negative_stock_allowed', false);
        $this->migrator->add('shop.low_stock_threshold', 5);
    }
}
