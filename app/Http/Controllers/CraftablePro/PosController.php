<?php

namespace App\Http\Controllers\CraftablePro;

use App\Exceptions\InsufficientStockException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\OrderHeader;
use App\Models\OrderLine;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\Price;
use App\Models\SaleChannel;
use App\Models\Store;
use App\Services\InvoiceService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Settings\ShopSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\CraftablePro\Pos\CheckoutPosRequest;
use App\Http\Requests\CraftablePro\Pos\IndexPosRequest;
use App\Http\Requests\CraftablePro\Pos\SearchPosRequest;


class PosController extends Controller{

    public function index(IndexPosRequest $request, ShopSettings $settings): Response
    {
        return Inertia::render('Pos/Index', [
            'customers' => Customer::orderBy('code')
                ->get(['id', 'code', 'first_name', 'last_name', 'company_name', 'is_company']),
            'payment_methods' => PaymentMethod::orderBy('name')->get(['id', 'name']),
            'currency'        => $settings->currency_symbol,
            'receipt'         => session('pos_receipt'),
        ]);
    }

    public function search(SearchPosRequest $request): JsonResponse
{
    $q = trim((string) ($request->validated()['q'] ?? ''));

    $items = Item::query()
        ->where('is_active', true)
        ->whereHas('prices', fn ($p) => $p->active())          // only sellable items
        ->with('activePrice')                                  // one extra query, no N+1
        ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
            ->where('name', 'like', "%{$q}%")
            ->orWhere('sku_code', 'like', "%{$q}%")))
        ->orderBy('name')
        ->limit(30)                                            // 30 *sellable* items now
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
}
?>