<?php

namespace App\Http\Controllers\Shop;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Models\DeliveryType;
use App\Services\CartService;
use App\Services\StorefrontCheckoutService;
use App\Settings\ShopSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function show(CartService $cart, ShopSettings $settings): Response|RedirectResponse
    {
        if (empty($cart->lines())) {
            return redirect('/products');
        }

        return Inertia::render('Shop/Checkout', [
            'lines'         => $cart->lines(),
            'summary'       => $cart->summary(),
            'deliveryTypes' => DeliveryType::orderBy('name')->get(['id', 'name']),
            'currency'      => $settings->currency_symbol,
        ]);
    }

    public function store(Request $request, CartService $cart, StorefrontCheckoutService $checkout): RedirectResponse
    {
        $lines = $cart->lines();
        if (empty($lines)) {
            return redirect('/products');
        }

        $data = $request->validate([
            'first_name'       => ['required', 'string', 'max:50'],
            'last_name'        => ['required', 'string', 'max:50'],
            'phone'            => ['required', 'string', 'max:50'],
            'email'            => ['nullable', 'email', 'max:255'],
            'billing_address'  => ['required', 'string', 'max:255'],
            'postal_code'      => ['nullable', 'string', 'max:20'],
            'delivery_type_id' => ['nullable', 'integer', 'exists:delivery_types,id'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $order = $checkout->place($lines, $data);
        } catch (InsufficientStockException $e) {
            return back()->with('error', 'Sorry, one or more items just went out of stock.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $cart->clear();

        return redirect()->route('shop.checkout.success')->with('order', $order);
    }

    public function success(ShopSettings $settings): Response|RedirectResponse
    {
        $order = session('order');
        if (! $order) {
            return redirect('/');
        }

        return Inertia::render('Shop/Confirmation', [
            'order'    => $order,
            'currency' => $settings->currency_symbol,
        ]);
    }
}
