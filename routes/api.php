<?phpuse Illuminate\Support\Facades\Route;



Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.item-categories.')->group(function () {
    Route::get('item-category', [App\Http\Controllers\Api\ItemCategoryController::class, 'index'])->name('index');
    Route::post('item-category', [App\Http\Controllers\Api\ItemCategoryController::class, 'store'])->name('store');
    Route::get('item-category/{itemCategory}', [App\Http\Controllers\Api\ItemCategoryController::class, 'show'])->name('show');
    Route::put('item-category/{itemCategory}', [App\Http\Controllers\Api\ItemCategoryController::class, 'update'])->name('update');
    Route::delete('item-category/{itemCategory}', [App\Http\Controllers\Api\ItemCategoryController::class, 'destroy'])->name('destroy');
});
use Illuminate\Support\Facades\Route;



Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.item-categories.')->group(function () {
    Route::get('item-category', [App\Http\Controllers\Api\ItemCategoryController::class, 'index'])->name('index');
    Route::post('item-category', [App\Http\Controllers\Api\ItemCategoryController::class, 'store'])->name('store');
    Route::get('item-category/{itemCategory}', [App\Http\Controllers\Api\ItemCategoryController::class, 'show'])->name('show');
    Route::put('item-category/{itemCategory}', [App\Http\Controllers\Api\ItemCategoryController::class, 'update'])->name('update');
    Route::delete('item-category/{itemCategory}', [App\Http\Controllers\Api\ItemCategoryController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.countries.')->group(function () {
    Route::get('country', [App\Http\Controllers\Api\CountryController::class, 'index'])->name('index');
    Route::post('country', [App\Http\Controllers\Api\CountryController::class, 'store'])->name('store');
    Route::get('country/{country}', [App\Http\Controllers\Api\CountryController::class, 'show'])->name('show');
    Route::put('country/{country}', [App\Http\Controllers\Api\CountryController::class, 'update'])->name('update');
    Route::delete('country/{country}', [App\Http\Controllers\Api\CountryController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.time-zones.')->group(function () {
    Route::get('time-zone', [App\Http\Controllers\Api\TimeZoneController::class, 'index'])->name('index');
    Route::post('time-zone', [App\Http\Controllers\Api\TimeZoneController::class, 'store'])->name('store');
    Route::get('time-zone/{timeZone}', [App\Http\Controllers\Api\TimeZoneController::class, 'show'])->name('show');
    Route::put('time-zone/{timeZone}', [App\Http\Controllers\Api\TimeZoneController::class, 'update'])->name('update');
    Route::delete('time-zone/{timeZone}', [App\Http\Controllers\Api\TimeZoneController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.currencies.')->group(function () {
    Route::get('currency', [App\Http\Controllers\Api\CurrencyController::class, 'index'])->name('index');
    Route::post('currency', [App\Http\Controllers\Api\CurrencyController::class, 'store'])->name('store');
    Route::get('currency/{currency}', [App\Http\Controllers\Api\CurrencyController::class, 'show'])->name('show');
    Route::put('currency/{currency}', [App\Http\Controllers\Api\CurrencyController::class, 'update'])->name('update');
    Route::delete('currency/{currency}', [App\Http\Controllers\Api\CurrencyController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.languages.')->group(function () {
    Route::get('language', [App\Http\Controllers\Api\LanguageController::class, 'index'])->name('index');
    Route::post('language', [App\Http\Controllers\Api\LanguageController::class, 'store'])->name('store');
    Route::get('language/{language}', [App\Http\Controllers\Api\LanguageController::class, 'show'])->name('show');
    Route::put('language/{language}', [App\Http\Controllers\Api\LanguageController::class, 'update'])->name('update');
    Route::delete('language/{language}', [App\Http\Controllers\Api\LanguageController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.measure-units.')->group(function () {
    Route::get('measure-unit', [App\Http\Controllers\Api\MeasureUnitController::class, 'index'])->name('index');
    Route::post('measure-unit', [App\Http\Controllers\Api\MeasureUnitController::class, 'store'])->name('store');
    Route::get('measure-unit/{measureUnit}', [App\Http\Controllers\Api\MeasureUnitController::class, 'show'])->name('show');
    Route::put('measure-unit/{measureUnit}', [App\Http\Controllers\Api\MeasureUnitController::class, 'update'])->name('update');
    Route::delete('measure-unit/{measureUnit}', [App\Http\Controllers\Api\MeasureUnitController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.sale-channels.')->group(function () {
    Route::get('sale-channel', [App\Http\Controllers\Api\SaleChannelController::class, 'index'])->name('index');
    Route::post('sale-channel', [App\Http\Controllers\Api\SaleChannelController::class, 'store'])->name('store');
    Route::get('sale-channel/{saleChannel}', [App\Http\Controllers\Api\SaleChannelController::class, 'show'])->name('show');
    Route::put('sale-channel/{saleChannel}', [App\Http\Controllers\Api\SaleChannelController::class, 'update'])->name('update');
    Route::delete('sale-channel/{saleChannel}', [App\Http\Controllers\Api\SaleChannelController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.delivery-types.')->group(function () {
    Route::get('delivery-type', [App\Http\Controllers\Api\DeliveryTypeController::class, 'index'])->name('index');
    Route::post('delivery-type', [App\Http\Controllers\Api\DeliveryTypeController::class, 'store'])->name('store');
    Route::get('delivery-type/{deliveryType}', [App\Http\Controllers\Api\DeliveryTypeController::class, 'show'])->name('show');
    Route::put('delivery-type/{deliveryType}', [App\Http\Controllers\Api\DeliveryTypeController::class, 'update'])->name('update');
    Route::delete('delivery-type/{deliveryType}', [App\Http\Controllers\Api\DeliveryTypeController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.payment-methods.')->group(function () {
    Route::get('payment-method', [App\Http\Controllers\Api\PaymentMethodController::class, 'index'])->name('index');
    Route::post('payment-method', [App\Http\Controllers\Api\PaymentMethodController::class, 'store'])->name('store');
    Route::get('payment-method/{paymentMethod}', [App\Http\Controllers\Api\PaymentMethodController::class, 'show'])->name('show');
    Route::put('payment-method/{paymentMethod}', [App\Http\Controllers\Api\PaymentMethodController::class, 'update'])->name('update');
    Route::delete('payment-method/{paymentMethod}', [App\Http\Controllers\Api\PaymentMethodController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.payment-times.')->group(function () {
    Route::get('payment-time', [App\Http\Controllers\Api\PaymentTimeController::class, 'index'])->name('index');
    Route::post('payment-time', [App\Http\Controllers\Api\PaymentTimeController::class, 'store'])->name('store');
    Route::get('payment-time/{paymentTime}', [App\Http\Controllers\Api\PaymentTimeController::class, 'show'])->name('show');
    Route::put('payment-time/{paymentTime}', [App\Http\Controllers\Api\PaymentTimeController::class, 'update'])->name('update');
    Route::delete('payment-time/{paymentTime}', [App\Http\Controllers\Api\PaymentTimeController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.order-statuses.')->group(function () {
    Route::get('order-status', [App\Http\Controllers\Api\OrderStatusController::class, 'index'])->name('index');
    Route::post('order-status', [App\Http\Controllers\Api\OrderStatusController::class, 'store'])->name('store');
    Route::get('order-status/{orderStatus}', [App\Http\Controllers\Api\OrderStatusController::class, 'show'])->name('show');
    Route::put('order-status/{orderStatus}', [App\Http\Controllers\Api\OrderStatusController::class, 'update'])->name('update');
    Route::delete('order-status/{orderStatus}', [App\Http\Controllers\Api\OrderStatusController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.loyalty-card-types.')->group(function () {
    Route::get('loyalty-card-type', [App\Http\Controllers\Api\LoyaltyCardTypeController::class, 'index'])->name('index');
    Route::post('loyalty-card-type', [App\Http\Controllers\Api\LoyaltyCardTypeController::class, 'store'])->name('store');
    Route::get('loyalty-card-type/{loyaltyCardType}', [App\Http\Controllers\Api\LoyaltyCardTypeController::class, 'show'])->name('show');
    Route::put('loyalty-card-type/{loyaltyCardType}', [App\Http\Controllers\Api\LoyaltyCardTypeController::class, 'update'])->name('update');
    Route::delete('loyalty-card-type/{loyaltyCardType}', [App\Http\Controllers\Api\LoyaltyCardTypeController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.regions.')->group(function () {
    Route::get('region', [App\Http\Controllers\Api\RegionController::class, 'index'])->name('index');
    Route::post('region', [App\Http\Controllers\Api\RegionController::class, 'store'])->name('store');
    Route::get('region/{region}', [App\Http\Controllers\Api\RegionController::class, 'show'])->name('show');
    Route::put('region/{region}', [App\Http\Controllers\Api\RegionController::class, 'update'])->name('update');
    Route::delete('region/{region}', [App\Http\Controllers\Api\RegionController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.cities.')->group(function () {
    Route::get('city', [App\Http\Controllers\Api\CityController::class, 'index'])->name('index');
    Route::post('city', [App\Http\Controllers\Api\CityController::class, 'store'])->name('store');
    Route::get('city/{city}', [App\Http\Controllers\Api\CityController::class, 'show'])->name('show');
    Route::put('city/{city}', [App\Http\Controllers\Api\CityController::class, 'update'])->name('update');
    Route::delete('city/{city}', [App\Http\Controllers\Api\CityController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.stores.')->group(function () {
    Route::get('store', [App\Http\Controllers\Api\StoreController::class, 'index'])->name('index');
    Route::post('store', [App\Http\Controllers\Api\StoreController::class, 'store'])->name('store');
    Route::get('store/{store}', [App\Http\Controllers\Api\StoreController::class, 'show'])->name('show');
    Route::put('store/{store}', [App\Http\Controllers\Api\StoreController::class, 'update'])->name('update');
    Route::delete('store/{store}', [App\Http\Controllers\Api\StoreController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.tax-types.')->group(function () {
    Route::get('tax-type', [App\Http\Controllers\Api\TaxTypeController::class, 'index'])->name('index');
    Route::post('tax-type', [App\Http\Controllers\Api\TaxTypeController::class, 'store'])->name('store');
    Route::get('tax-type/{taxType}', [App\Http\Controllers\Api\TaxTypeController::class, 'show'])->name('show');
    Route::put('tax-type/{taxType}', [App\Http\Controllers\Api\TaxTypeController::class, 'update'])->name('update');
    Route::delete('tax-type/{taxType}', [App\Http\Controllers\Api\TaxTypeController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.suppliers.')->group(function () {
    Route::get('supplier', [App\Http\Controllers\Api\SupplierController::class, 'index'])->name('index');
    Route::post('supplier', [App\Http\Controllers\Api\SupplierController::class, 'store'])->name('store');
    Route::get('supplier/{supplier}', [App\Http\Controllers\Api\SupplierController::class, 'show'])->name('show');
    Route::put('supplier/{supplier}', [App\Http\Controllers\Api\SupplierController::class, 'update'])->name('update');
    Route::delete('supplier/{supplier}', [App\Http\Controllers\Api\SupplierController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.customers.')->group(function () {
    Route::get('customer', [App\Http\Controllers\Api\CustomerController::class, 'index'])->name('index');
    Route::post('customer', [App\Http\Controllers\Api\CustomerController::class, 'store'])->name('store');
    Route::get('customer/{customer}', [App\Http\Controllers\Api\CustomerController::class, 'show'])->name('show');
    Route::put('customer/{customer}', [App\Http\Controllers\Api\CustomerController::class, 'update'])->name('update');
    Route::delete('customer/{customer}', [App\Http\Controllers\Api\CustomerController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.loyalty-cards.')->group(function () {
    Route::get('loyalty-card', [App\Http\Controllers\Api\LoyaltyCardController::class, 'index'])->name('index');
    Route::post('loyalty-card', [App\Http\Controllers\Api\LoyaltyCardController::class, 'store'])->name('store');
    Route::get('loyalty-card/{loyaltyCard}', [App\Http\Controllers\Api\LoyaltyCardController::class, 'show'])->name('show');
    Route::put('loyalty-card/{loyaltyCard}', [App\Http\Controllers\Api\LoyaltyCardController::class, 'update'])->name('update');
    Route::delete('loyalty-card/{loyaltyCard}', [App\Http\Controllers\Api\LoyaltyCardController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.items.')->group(function () {
    Route::get('item', [App\Http\Controllers\Api\ItemController::class, 'index'])->name('index');
    Route::post('item', [App\Http\Controllers\Api\ItemController::class, 'store'])->name('store');
    Route::get('item/{item}', [App\Http\Controllers\Api\ItemController::class, 'show'])->name('show');
    Route::put('item/{item}', [App\Http\Controllers\Api\ItemController::class, 'update'])->name('update');
    Route::delete('item/{item}', [App\Http\Controllers\Api\ItemController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.bar-codes.')->group(function () {
    Route::get('bar-code', [App\Http\Controllers\Api\BarCodeController::class, 'index'])->name('index');
    Route::post('bar-code', [App\Http\Controllers\Api\BarCodeController::class, 'store'])->name('store');
    Route::get('bar-code/{barCode}', [App\Http\Controllers\Api\BarCodeController::class, 'show'])->name('show');
    Route::put('bar-code/{barCode}', [App\Http\Controllers\Api\BarCodeController::class, 'update'])->name('update');
    Route::delete('bar-code/{barCode}', [App\Http\Controllers\Api\BarCodeController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.prices.')->group(function () {
    Route::get('price', [App\Http\Controllers\Api\PriceController::class, 'index'])->name('index');
    Route::post('price', [App\Http\Controllers\Api\PriceController::class, 'store'])->name('store');
    Route::get('price/{price}', [App\Http\Controllers\Api\PriceController::class, 'show'])->name('show');
    Route::put('price/{price}', [App\Http\Controllers\Api\PriceController::class, 'update'])->name('update');
    Route::delete('price/{price}', [App\Http\Controllers\Api\PriceController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.supplier-tax-types.')->group(function () {
    Route::get('supplier-tax-type', [App\Http\Controllers\Api\SupplierTaxTypeController::class, 'index'])->name('index');
    Route::post('supplier-tax-type', [App\Http\Controllers\Api\SupplierTaxTypeController::class, 'store'])->name('store');
    Route::get('supplier-tax-type/{supplierTaxType}', [App\Http\Controllers\Api\SupplierTaxTypeController::class, 'show'])->name('show');
    Route::put('supplier-tax-type/{supplierTaxType}', [App\Http\Controllers\Api\SupplierTaxTypeController::class, 'update'])->name('update');
    Route::delete('supplier-tax-type/{supplierTaxType}', [App\Http\Controllers\Api\SupplierTaxTypeController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.supplier-item-tax-types.')->group(function () {
    Route::get('supplier-item-tax-type', [App\Http\Controllers\Api\SupplierItemTaxTypeController::class, 'index'])->name('index');
    Route::post('supplier-item-tax-type', [App\Http\Controllers\Api\SupplierItemTaxTypeController::class, 'store'])->name('store');
    Route::get('supplier-item-tax-type/{supplierItemTaxType}', [App\Http\Controllers\Api\SupplierItemTaxTypeController::class, 'show'])->name('show');
    Route::put('supplier-item-tax-type/{supplierItemTaxType}', [App\Http\Controllers\Api\SupplierItemTaxTypeController::class, 'update'])->name('update');
    Route::delete('supplier-item-tax-type/{supplierItemTaxType}', [App\Http\Controllers\Api\SupplierItemTaxTypeController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.discount-types.')->group(function () {
    Route::get('discount-type', [App\Http\Controllers\Api\DiscountTypeController::class, 'index'])->name('index');
    Route::post('discount-type', [App\Http\Controllers\Api\DiscountTypeController::class, 'store'])->name('store');
    Route::get('discount-type/{discountType}', [App\Http\Controllers\Api\DiscountTypeController::class, 'show'])->name('show');
    Route::put('discount-type/{discountType}', [App\Http\Controllers\Api\DiscountTypeController::class, 'update'])->name('update');
    Route::delete('discount-type/{discountType}', [App\Http\Controllers\Api\DiscountTypeController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.discounts.')->group(function () {
    Route::get('discount', [App\Http\Controllers\Api\DiscountController::class, 'index'])->name('index');
    Route::post('discount', [App\Http\Controllers\Api\DiscountController::class, 'store'])->name('store');
    Route::get('discount/{discount}', [App\Http\Controllers\Api\DiscountController::class, 'show'])->name('show');
    Route::put('discount/{discount}', [App\Http\Controllers\Api\DiscountController::class, 'update'])->name('update');
    Route::delete('discount/{discount}', [App\Http\Controllers\Api\DiscountController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.payment-terms.')->group(function () {
    Route::get('payment-term', [App\Http\Controllers\Api\PaymentTermController::class, 'index'])->name('index');
    Route::post('payment-term', [App\Http\Controllers\Api\PaymentTermController::class, 'store'])->name('store');
    Route::get('payment-term/{paymentTerm}', [App\Http\Controllers\Api\PaymentTermController::class, 'show'])->name('show');
    Route::put('payment-term/{paymentTerm}', [App\Http\Controllers\Api\PaymentTermController::class, 'update'])->name('update');
    Route::delete('payment-term/{paymentTerm}', [App\Http\Controllers\Api\PaymentTermController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.order-headers.')->group(function () {
    Route::get('order-header', [App\Http\Controllers\Api\OrderHeaderController::class, 'index'])->name('index');
    Route::post('order-header', [App\Http\Controllers\Api\OrderHeaderController::class, 'store'])->name('store');
    Route::get('order-header/{orderHeader}', [App\Http\Controllers\Api\OrderHeaderController::class, 'show'])->name('show');
    Route::put('order-header/{orderHeader}', [App\Http\Controllers\Api\OrderHeaderController::class, 'update'])->name('update');
    Route::delete('order-header/{orderHeader}', [App\Http\Controllers\Api\OrderHeaderController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.order-lines.')->group(function () {
    Route::get('order-line', [App\Http\Controllers\Api\OrderLineController::class, 'index'])->name('index');
    Route::post('order-line', [App\Http\Controllers\Api\OrderLineController::class, 'store'])->name('store');
    Route::get('order-line/{orderLine}', [App\Http\Controllers\Api\OrderLineController::class, 'show'])->name('show');
    Route::put('order-line/{orderLine}', [App\Http\Controllers\Api\OrderLineController::class, 'update'])->name('update');
    Route::delete('order-line/{orderLine}', [App\Http\Controllers\Api\OrderLineController::class, 'destroy'])->name('destroy');
});


Route::middleware('auth:craftable-pro-api')->name('craftable-pro-api.order-status-histories.')->group(function () {
    Route::get('order-status-history', [App\Http\Controllers\Api\OrderStatusHistoryController::class, 'index'])->name('index');
    Route::post('order-status-history', [App\Http\Controllers\Api\OrderStatusHistoryController::class, 'store'])->name('store');
    Route::get('order-status-history/{orderStatusHistory}', [App\Http\Controllers\Api\OrderStatusHistoryController::class, 'show'])->name('show');
    Route::put('order-status-history/{orderStatusHistory}', [App\Http\Controllers\Api\OrderStatusHistoryController::class, 'update'])->name('update');
    Route::delete('order-status-history/{orderStatusHistory}', [App\Http\Controllers\Api\OrderStatusHistoryController::class, 'destroy'])->name('destroy');
});
