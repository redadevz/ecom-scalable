<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Settings\ShopSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(CartService $cart, ShopSettings $settings): Response
    {
        return Inertia::render('Shop/Cart', [
            'lines'    => $cart->lines(),
            'summary'  => $cart->summary(),
            'currency' => $settings->currency_symbol,
        ]);
    }

    /** JSON snapshot for the mini-cart drawer. */
    public function data(CartService $cart): JsonResponse
    {
        return response()->json($this->payload($cart));
    }

    public function add(Request $request, CartService $cart): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'item_id'  => ['required', 'integer', 'exists:items,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $cart->add($data['item_id'], $data['quantity'] ?? 1);

        return $this->respond($request, $cart, 'Added to cart');
    }

    public function update(Request $request, int $item, CartService $cart): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart->set($item, $data['quantity']);

        return $this->respond($request, $cart, null);
    }

    public function remove(Request $request, int $item, CartService $cart): JsonResponse|RedirectResponse
    {
        $cart->remove($item);

        return $this->respond($request, $cart, 'Item removed');
    }

    /** JSON for XHR (drawer), Inertia redirect for classic form posts. */
    private function respond(Request $request, CartService $cart, ?string $message): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json($this->payload($cart) + ['message' => $message]);
        }

        return back()->with($message ? ['message' => $message] : []);
    }

    private function payload(CartService $cart): array
    {
        $summary = $cart->summary();

        return [
            'lines'   => $cart->lines(),
            'summary' => $summary,
            'count'   => $summary['count'],
        ];
    }
}
