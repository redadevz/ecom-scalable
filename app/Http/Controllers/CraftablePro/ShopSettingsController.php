<?php

namespace App\Http\Controllers\CraftablePro;

use App\Http\Controllers\Controller;
use App\Settings\ShopSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopSettingsController extends Controller
{
    public function edit(ShopSettings $settings): Response
    {
        return Inertia::render('Settings/Shop', [
            'settings' => [
                'currency_code' => $settings->currency_code,
                'currency_symbol' => $settings->currency_symbol,
                'default_tax_rate' => $settings->default_tax_rate,
                'negative_stock_allowed' => $settings->negative_stock_allowed,
                'low_stock_threshold' => $settings->low_stock_threshold,
            ],
            
        ]);
    }

    public function update(Request $request, ShopSettings $settings): RedirectResponse
    {
        $data = $request->validate([
            'currency_code' => ['required', 'string', 'max:8'],
            'currency_symbol'        => ['required', 'string', 'max:8'],
            'default_tax_rate'       => ['required', 'numeric', 'min:0', 'max:100'],
            'negative_stock_allowed' => ['required', 'boolean'],
            'low_stock_threshold'    => ['required', 'integer', 'min:0'],
        ]);

        $settings->currency_code          = $data['currency_code'];
        $settings->currency_symbol        = $data['currency_symbol'];
        $settings->default_tax_rate       = $data['default_tax_rate'];
        $settings->negative_stock_allowed = $data['negative_stock_allowed'];
        $settings->low_stock_threshold    = $data['low_stock_threshold'];
        $settings->save();

        return redirect()->back()->with(['message' => ___('craftable-pro', 'Operation successful')]);
    }
}
