<?php

namespace App\Http\Controllers\CraftablePro;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CraftablePro\Pos\CheckoutPosRequest;
use App\Http\Requests\CraftablePro\Pos\IndexPosRequest;
use App\Http\Requests\CraftablePro\Pos\SearchPosRequest;
use App\Models\Customer;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\PaymentMethod;
use App\Services\PosService;
use App\Settings\ShopSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Point of Sale — a single fast counter screen.
 *
 * Thin controller: it only handles HTTP (render the screen, item search, and
 * mapping domain errors to a 422). The sale itself is orchestrated by
 * PosService over the same tested engine the admin uses.
 */
class PosController extends Controller
{
    public function index(IndexPosRequest $request, ShopSettings $settings): Response
    {
        return Inertia::render('Pos/Index', [
            'customers' => Customer::orderBy('code')
                ->get(['id', 'code', 'first_name', 'last_name', 'company_name', 'is_company'])
                ->map(fn ($c) => [
                    'id'    => $c->id,
                    'label' => $this->customerName($c) . " ({$c->code})",
                ]),
            'categories'      => ItemCategory::where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'payment_methods' => PaymentMethod::orderBy('name')->get(['id', 'name']),
            'currency'        => $settings->currency_symbol,
            'receipt'         => session('pos_receipt'),
        ]);
    }

    /** Item listing / search — sellable items (active price), optional query + category filter. */
    public function search(SearchPosRequest $request): JsonResponse
    {
        $data       = $request->validated();
        $q          = trim((string) ($data['q'] ?? ''));
        $categoryId = $data['category_id'] ?? null;

        $items = Item::query()
            ->where('is_active', true)
            ->whereHas('prices', fn ($p) => $p->active())          // only sellable items
            ->with('activePrice')                                  // one extra query, no N+1
            ->when($categoryId, fn ($query) => $query->where('item_category_id', $categoryId))
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('sku_code', 'like', "%{$q}%")))
            ->orderBy('name')
            ->limit(60)
            ->get(['id', 'name', 'sku_code', 'is_service', 'current_stock_quantity']);

        $results = $items->map(fn (Item $item) => [
            'id'         => $item->id,
            'name'       => $item->name,
            'sku'        => $item->sku_code,
            'is_service' => (bool) $item->is_service,
            'stock'      => (int) $item->current_stock_quantity,
            'unit_price' => (float) $item->activePrice->price_after_tax,
        ]);

        return response()->json($results);
    }

    /** Ring up the sale (authorized + validated by CheckoutPosRequest). */
    public function checkout(CheckoutPosRequest $request, PosService $pos): RedirectResponse
    {
        try {
            $receipt = $pos->checkout($request->validated(), auth('craftable-pro')->id());
        } catch (InsufficientStockException $e) {
            throw ValidationException::withMessages([
                'lines' => 'Not enough stock for one or more items (enable negative stock in Shop Settings to override).',
            ]);
        } catch (\RuntimeException $e) {
            throw ValidationException::withMessages(['lines' => $e->getMessage()]);
        }

        return redirect()->route('craftable-pro.pos')->with('pos_receipt', $receipt);
    }

    private function customerName($c): string
    {
        if (! $c) {
            return 'Walk-in';
        }

        return trim($c->is_company ? (string) $c->company_name : "{$c->first_name} {$c->last_name}") ?: 'Customer';
    }
}
