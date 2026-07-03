<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::craftablePro('admin');

/* Dashboard home override — real KPI stats (must come AFTER craftablePro macro) */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('/', [App\Http\Controllers\CraftablePro\DashboardController::class, 'index'])->name('home');
    Route::get('items/grid', [App\Http\Controllers\CraftablePro\ItemController::class, 'grid'])->name('items.grid');
    Route::get('reports/sales', [App\Http\Controllers\CraftablePro\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/sales/export', [App\Http\Controllers\CraftablePro\ReportController::class, 'salesExport'])->name('reports.sales.export');
    Route::get('reports/stock', [App\Http\Controllers\CraftablePro\ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/stock/export', [App\Http\Controllers\CraftablePro\ReportController::class, 'stockExport'])->name('reports.stock.export');
    Route::get('reports/purchases', [App\Http\Controllers\CraftablePro\ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('reports/purchases/export', [App\Http\Controllers\CraftablePro\ReportController::class, 'purchasesExport'])->name('reports.purchases.export');
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('item-categories', [App\Http\Controllers\CraftablePro\ItemCategoryController::class, 'index'])->name('item-categories.index');
    Route::get('item-categories/create', [App\Http\Controllers\CraftablePro\ItemCategoryController::class, 'create'])->name('item-categories.create');
    Route::post('item-categories', [App\Http\Controllers\CraftablePro\ItemCategoryController::class, 'store'])->name('item-categories.store');
    Route::get('item-categories/edit/{itemCategory}', [App\Http\Controllers\CraftablePro\ItemCategoryController::class, 'edit'])->name('item-categories.edit');
    Route::match(['put', 'patch'], 'item-categories/{itemCategory}', [App\Http\Controllers\CraftablePro\ItemCategoryController::class, 'update'])->name('item-categories.update');
    Route::delete('item-categories/{itemCategory}', [App\Http\Controllers\CraftablePro\ItemCategoryController::class, 'destroy'])->name('item-categories.destroy');
    Route::post('item-categories/bulk-destroy', [App\Http\Controllers\CraftablePro\ItemCategoryController::class, 'bulkDestroy'])->name('item-categories.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('countries', [App\Http\Controllers\CraftablePro\CountryController::class, 'index'])->name('countries.index');
    Route::get('countries/create', [App\Http\Controllers\CraftablePro\CountryController::class, 'create'])->name('countries.create');
    Route::post('countries', [App\Http\Controllers\CraftablePro\CountryController::class, 'store'])->name('countries.store');
    Route::get('countries/edit/{country}', [App\Http\Controllers\CraftablePro\CountryController::class, 'edit'])->name('countries.edit');
    Route::match(['put', 'patch'], 'countries/{country}', [App\Http\Controllers\CraftablePro\CountryController::class, 'update'])->name('countries.update');
    Route::delete('countries/{country}', [App\Http\Controllers\CraftablePro\CountryController::class, 'destroy'])->name('countries.destroy');
    Route::post('countries/bulk-destroy', [App\Http\Controllers\CraftablePro\CountryController::class, 'bulkDestroy'])->name('countries.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('time-zones', [App\Http\Controllers\CraftablePro\TimeZoneController::class, 'index'])->name('time-zones.index');
    Route::get('time-zones/create', [App\Http\Controllers\CraftablePro\TimeZoneController::class, 'create'])->name('time-zones.create');
    Route::post('time-zones', [App\Http\Controllers\CraftablePro\TimeZoneController::class, 'store'])->name('time-zones.store');
    Route::get('time-zones/edit/{timeZone}', [App\Http\Controllers\CraftablePro\TimeZoneController::class, 'edit'])->name('time-zones.edit');
    Route::match(['put', 'patch'], 'time-zones/{timeZone}', [App\Http\Controllers\CraftablePro\TimeZoneController::class, 'update'])->name('time-zones.update');
    Route::delete('time-zones/{timeZone}', [App\Http\Controllers\CraftablePro\TimeZoneController::class, 'destroy'])->name('time-zones.destroy');
    Route::post('time-zones/bulk-destroy', [App\Http\Controllers\CraftablePro\TimeZoneController::class, 'bulkDestroy'])->name('time-zones.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('currencies', [App\Http\Controllers\CraftablePro\CurrencyController::class, 'index'])->name('currencies.index');
    Route::get('currencies/create', [App\Http\Controllers\CraftablePro\CurrencyController::class, 'create'])->name('currencies.create');
    Route::post('currencies', [App\Http\Controllers\CraftablePro\CurrencyController::class, 'store'])->name('currencies.store');
    Route::get('currencies/edit/{currency}', [App\Http\Controllers\CraftablePro\CurrencyController::class, 'edit'])->name('currencies.edit');
    Route::match(['put', 'patch'], 'currencies/{currency}', [App\Http\Controllers\CraftablePro\CurrencyController::class, 'update'])->name('currencies.update');
    Route::delete('currencies/{currency}', [App\Http\Controllers\CraftablePro\CurrencyController::class, 'destroy'])->name('currencies.destroy');
    Route::post('currencies/bulk-destroy', [App\Http\Controllers\CraftablePro\CurrencyController::class, 'bulkDestroy'])->name('currencies.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('languages', [App\Http\Controllers\CraftablePro\LanguageController::class, 'index'])->name('languages.index');
    Route::get('languages/create', [App\Http\Controllers\CraftablePro\LanguageController::class, 'create'])->name('languages.create');
    Route::post('languages', [App\Http\Controllers\CraftablePro\LanguageController::class, 'store'])->name('languages.store');
    Route::get('languages/edit/{language}', [App\Http\Controllers\CraftablePro\LanguageController::class, 'edit'])->name('languages.edit');
    Route::match(['put', 'patch'], 'languages/{language}', [App\Http\Controllers\CraftablePro\LanguageController::class, 'update'])->name('languages.update');
    Route::delete('languages/{language}', [App\Http\Controllers\CraftablePro\LanguageController::class, 'destroy'])->name('languages.destroy');
    Route::post('languages/bulk-destroy', [App\Http\Controllers\CraftablePro\LanguageController::class, 'bulkDestroy'])->name('languages.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('measure-units', [App\Http\Controllers\CraftablePro\MeasureUnitController::class, 'index'])->name('measure-units.index');
    Route::get('measure-units/create', [App\Http\Controllers\CraftablePro\MeasureUnitController::class, 'create'])->name('measure-units.create');
    Route::post('measure-units', [App\Http\Controllers\CraftablePro\MeasureUnitController::class, 'store'])->name('measure-units.store');
    Route::get('measure-units/edit/{measureUnit}', [App\Http\Controllers\CraftablePro\MeasureUnitController::class, 'edit'])->name('measure-units.edit');
    Route::match(['put', 'patch'], 'measure-units/{measureUnit}', [App\Http\Controllers\CraftablePro\MeasureUnitController::class, 'update'])->name('measure-units.update');
    Route::delete('measure-units/{measureUnit}', [App\Http\Controllers\CraftablePro\MeasureUnitController::class, 'destroy'])->name('measure-units.destroy');
    Route::post('measure-units/bulk-destroy', [App\Http\Controllers\CraftablePro\MeasureUnitController::class, 'bulkDestroy'])->name('measure-units.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('sale-channels', [App\Http\Controllers\CraftablePro\SaleChannelController::class, 'index'])->name('sale-channels.index');
    Route::get('sale-channels/create', [App\Http\Controllers\CraftablePro\SaleChannelController::class, 'create'])->name('sale-channels.create');
    Route::post('sale-channels', [App\Http\Controllers\CraftablePro\SaleChannelController::class, 'store'])->name('sale-channels.store');
    Route::get('sale-channels/edit/{saleChannel}', [App\Http\Controllers\CraftablePro\SaleChannelController::class, 'edit'])->name('sale-channels.edit');
    Route::match(['put', 'patch'], 'sale-channels/{saleChannel}', [App\Http\Controllers\CraftablePro\SaleChannelController::class, 'update'])->name('sale-channels.update');
    Route::delete('sale-channels/{saleChannel}', [App\Http\Controllers\CraftablePro\SaleChannelController::class, 'destroy'])->name('sale-channels.destroy');
    Route::post('sale-channels/bulk-destroy', [App\Http\Controllers\CraftablePro\SaleChannelController::class, 'bulkDestroy'])->name('sale-channels.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('delivery-types', [App\Http\Controllers\CraftablePro\DeliveryTypeController::class, 'index'])->name('delivery-types.index');
    Route::get('delivery-types/create', [App\Http\Controllers\CraftablePro\DeliveryTypeController::class, 'create'])->name('delivery-types.create');
    Route::post('delivery-types', [App\Http\Controllers\CraftablePro\DeliveryTypeController::class, 'store'])->name('delivery-types.store');
    Route::get('delivery-types/edit/{deliveryType}', [App\Http\Controllers\CraftablePro\DeliveryTypeController::class, 'edit'])->name('delivery-types.edit');
    Route::match(['put', 'patch'], 'delivery-types/{deliveryType}', [App\Http\Controllers\CraftablePro\DeliveryTypeController::class, 'update'])->name('delivery-types.update');
    Route::delete('delivery-types/{deliveryType}', [App\Http\Controllers\CraftablePro\DeliveryTypeController::class, 'destroy'])->name('delivery-types.destroy');
    Route::post('delivery-types/bulk-destroy', [App\Http\Controllers\CraftablePro\DeliveryTypeController::class, 'bulkDestroy'])->name('delivery-types.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('payment-methods', [App\Http\Controllers\CraftablePro\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('payment-methods/create', [App\Http\Controllers\CraftablePro\PaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('payment-methods', [App\Http\Controllers\CraftablePro\PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('payment-methods/edit/{paymentMethod}', [App\Http\Controllers\CraftablePro\PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::match(['put', 'patch'], 'payment-methods/{paymentMethod}', [App\Http\Controllers\CraftablePro\PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('payment-methods/{paymentMethod}', [App\Http\Controllers\CraftablePro\PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
    Route::post('payment-methods/bulk-destroy', [App\Http\Controllers\CraftablePro\PaymentMethodController::class, 'bulkDestroy'])->name('payment-methods.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('payment-times', [App\Http\Controllers\CraftablePro\PaymentTimeController::class, 'index'])->name('payment-times.index');
    Route::get('payment-times/create', [App\Http\Controllers\CraftablePro\PaymentTimeController::class, 'create'])->name('payment-times.create');
    Route::post('payment-times', [App\Http\Controllers\CraftablePro\PaymentTimeController::class, 'store'])->name('payment-times.store');
    Route::get('payment-times/edit/{paymentTime}', [App\Http\Controllers\CraftablePro\PaymentTimeController::class, 'edit'])->name('payment-times.edit');
    Route::match(['put', 'patch'], 'payment-times/{paymentTime}', [App\Http\Controllers\CraftablePro\PaymentTimeController::class, 'update'])->name('payment-times.update');
    Route::delete('payment-times/{paymentTime}', [App\Http\Controllers\CraftablePro\PaymentTimeController::class, 'destroy'])->name('payment-times.destroy');
    Route::post('payment-times/bulk-destroy', [App\Http\Controllers\CraftablePro\PaymentTimeController::class, 'bulkDestroy'])->name('payment-times.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('order-statuses', [App\Http\Controllers\CraftablePro\OrderStatusController::class, 'index'])->name('order-statuses.index');
    Route::get('order-statuses/create', [App\Http\Controllers\CraftablePro\OrderStatusController::class, 'create'])->name('order-statuses.create');
    Route::post('order-statuses', [App\Http\Controllers\CraftablePro\OrderStatusController::class, 'store'])->name('order-statuses.store');
    Route::get('order-statuses/edit/{orderStatus}', [App\Http\Controllers\CraftablePro\OrderStatusController::class, 'edit'])->name('order-statuses.edit');
    Route::match(['put', 'patch'], 'order-statuses/{orderStatus}', [App\Http\Controllers\CraftablePro\OrderStatusController::class, 'update'])->name('order-statuses.update');
    Route::delete('order-statuses/{orderStatus}', [App\Http\Controllers\CraftablePro\OrderStatusController::class, 'destroy'])->name('order-statuses.destroy');
    Route::post('order-statuses/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderStatusController::class, 'bulkDestroy'])->name('order-statuses.bulk-destroy');

    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('loyalty-card-types', [App\Http\Controllers\CraftablePro\LoyaltyCardTypeController::class, 'index'])->name('loyalty-card-types.index');
    Route::get('loyalty-card-types/create', [App\Http\Controllers\CraftablePro\LoyaltyCardTypeController::class, 'create'])->name('loyalty-card-types.create');
    Route::post('loyalty-card-types', [App\Http\Controllers\CraftablePro\LoyaltyCardTypeController::class, 'store'])->name('loyalty-card-types.store');
    Route::get('loyalty-card-types/edit/{loyaltyCardType}', [App\Http\Controllers\CraftablePro\LoyaltyCardTypeController::class, 'edit'])->name('loyalty-card-types.edit');
    Route::match(['put', 'patch'], 'loyalty-card-types/{loyaltyCardType}', [App\Http\Controllers\CraftablePro\LoyaltyCardTypeController::class, 'update'])->name('loyalty-card-types.update');
    Route::delete('loyalty-card-types/{loyaltyCardType}', [App\Http\Controllers\CraftablePro\LoyaltyCardTypeController::class, 'destroy'])->name('loyalty-card-types.destroy');
    Route::post('loyalty-card-types/bulk-destroy', [App\Http\Controllers\CraftablePro\LoyaltyCardTypeController::class, 'bulkDestroy'])->name('loyalty-card-types.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('regions', [App\Http\Controllers\CraftablePro\RegionController::class, 'index'])->name('regions.index');
    Route::get('regions/create', [App\Http\Controllers\CraftablePro\RegionController::class, 'create'])->name('regions.create');
    Route::post('regions', [App\Http\Controllers\CraftablePro\RegionController::class, 'store'])->name('regions.store');
    Route::get('regions/edit/{region}', [App\Http\Controllers\CraftablePro\RegionController::class, 'edit'])->name('regions.edit');
    Route::match(['put', 'patch'], 'regions/{region}', [App\Http\Controllers\CraftablePro\RegionController::class, 'update'])->name('regions.update');
    Route::delete('regions/{region}', [App\Http\Controllers\CraftablePro\RegionController::class, 'destroy'])->name('regions.destroy');
    Route::post('regions/bulk-destroy', [App\Http\Controllers\CraftablePro\RegionController::class, 'bulkDestroy'])->name('regions.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('cities', [App\Http\Controllers\CraftablePro\CityController::class, 'index'])->name('cities.index');
    Route::get('cities/create', [App\Http\Controllers\CraftablePro\CityController::class, 'create'])->name('cities.create');
    Route::post('cities', [App\Http\Controllers\CraftablePro\CityController::class, 'store'])->name('cities.store');
    Route::get('cities/edit/{city}', [App\Http\Controllers\CraftablePro\CityController::class, 'edit'])->name('cities.edit');
    Route::match(['put', 'patch'], 'cities/{city}', [App\Http\Controllers\CraftablePro\CityController::class, 'update'])->name('cities.update');
    Route::delete('cities/{city}', [App\Http\Controllers\CraftablePro\CityController::class, 'destroy'])->name('cities.destroy');
    Route::post('cities/bulk-destroy', [App\Http\Controllers\CraftablePro\CityController::class, 'bulkDestroy'])->name('cities.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('stores', [App\Http\Controllers\CraftablePro\StoreController::class, 'index'])->name('stores.index');
    Route::get('stores/create', [App\Http\Controllers\CraftablePro\StoreController::class, 'create'])->name('stores.create');
    Route::post('stores', [App\Http\Controllers\CraftablePro\StoreController::class, 'store'])->name('stores.store');
    Route::get('stores/edit/{store}', [App\Http\Controllers\CraftablePro\StoreController::class, 'edit'])->name('stores.edit');
    Route::match(['put', 'patch'], 'stores/{store}', [App\Http\Controllers\CraftablePro\StoreController::class, 'update'])->name('stores.update');
    Route::delete('stores/{store}', [App\Http\Controllers\CraftablePro\StoreController::class, 'destroy'])->name('stores.destroy');
    Route::post('stores/bulk-destroy', [App\Http\Controllers\CraftablePro\StoreController::class, 'bulkDestroy'])->name('stores.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('tax-types', [App\Http\Controllers\CraftablePro\TaxTypeController::class, 'index'])->name('tax-types.index');
    Route::get('tax-types/create', [App\Http\Controllers\CraftablePro\TaxTypeController::class, 'create'])->name('tax-types.create');
    Route::post('tax-types', [App\Http\Controllers\CraftablePro\TaxTypeController::class, 'store'])->name('tax-types.store');
    Route::get('tax-types/edit/{taxType}', [App\Http\Controllers\CraftablePro\TaxTypeController::class, 'edit'])->name('tax-types.edit');
    Route::match(['put', 'patch'], 'tax-types/{taxType}', [App\Http\Controllers\CraftablePro\TaxTypeController::class, 'update'])->name('tax-types.update');
    Route::delete('tax-types/{taxType}', [App\Http\Controllers\CraftablePro\TaxTypeController::class, 'destroy'])->name('tax-types.destroy');
    Route::post('tax-types/bulk-destroy', [App\Http\Controllers\CraftablePro\TaxTypeController::class, 'bulkDestroy'])->name('tax-types.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('suppliers', [App\Http\Controllers\CraftablePro\SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/create', [App\Http\Controllers\CraftablePro\SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('suppliers', [App\Http\Controllers\CraftablePro\SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('suppliers/edit/{supplier}', [App\Http\Controllers\CraftablePro\SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::match(['put', 'patch'], 'suppliers/{supplier}', [App\Http\Controllers\CraftablePro\SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('suppliers/{supplier}', [App\Http\Controllers\CraftablePro\SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::post('suppliers/bulk-destroy', [App\Http\Controllers\CraftablePro\SupplierController::class, 'bulkDestroy'])->name('suppliers.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('customers', [App\Http\Controllers\CraftablePro\CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [App\Http\Controllers\CraftablePro\CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [App\Http\Controllers\CraftablePro\CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/edit/{customer}', [App\Http\Controllers\CraftablePro\CustomerController::class, 'edit'])->name('customers.edit');
    Route::match(['put', 'patch'], 'customers/{customer}', [App\Http\Controllers\CraftablePro\CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{customer}', [App\Http\Controllers\CraftablePro\CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::post('customers/bulk-destroy', [App\Http\Controllers\CraftablePro\CustomerController::class, 'bulkDestroy'])->name('customers.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('loyalty-cards', [App\Http\Controllers\CraftablePro\LoyaltyCardController::class, 'index'])->name('loyalty-cards.index');
    Route::get('loyalty-cards/create', [App\Http\Controllers\CraftablePro\LoyaltyCardController::class, 'create'])->name('loyalty-cards.create');
    Route::post('loyalty-cards', [App\Http\Controllers\CraftablePro\LoyaltyCardController::class, 'store'])->name('loyalty-cards.store');
    Route::get('loyalty-cards/edit/{loyaltyCard}', [App\Http\Controllers\CraftablePro\LoyaltyCardController::class, 'edit'])->name('loyalty-cards.edit');
    Route::match(['put', 'patch'], 'loyalty-cards/{loyaltyCard}', [App\Http\Controllers\CraftablePro\LoyaltyCardController::class, 'update'])->name('loyalty-cards.update');
    Route::delete('loyalty-cards/{loyaltyCard}', [App\Http\Controllers\CraftablePro\LoyaltyCardController::class, 'destroy'])->name('loyalty-cards.destroy');
    Route::post('loyalty-cards/bulk-destroy', [App\Http\Controllers\CraftablePro\LoyaltyCardController::class, 'bulkDestroy'])->name('loyalty-cards.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('items', [App\Http\Controllers\CraftablePro\ItemController::class, 'index'])->name('items.index');
    Route::get('items/create', [App\Http\Controllers\CraftablePro\ItemController::class, 'create'])->name('items.create');
    Route::post('items', [App\Http\Controllers\CraftablePro\ItemController::class, 'store'])->name('items.store');
    Route::get('items/edit/{item}', [App\Http\Controllers\CraftablePro\ItemController::class, 'edit'])->name('items.edit');
    Route::match(['put', 'patch'], 'items/{item}', [App\Http\Controllers\CraftablePro\ItemController::class, 'update'])->name('items.update');
    Route::delete('items/{item}', [App\Http\Controllers\CraftablePro\ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('items/bulk-destroy', [App\Http\Controllers\CraftablePro\ItemController::class, 'bulkDestroy'])->name('items.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('bar-codes', [App\Http\Controllers\CraftablePro\BarCodeController::class, 'index'])->name('bar-codes.index');
    Route::get('bar-codes/create', [App\Http\Controllers\CraftablePro\BarCodeController::class, 'create'])->name('bar-codes.create');
    Route::post('bar-codes', [App\Http\Controllers\CraftablePro\BarCodeController::class, 'store'])->name('bar-codes.store');
    Route::get('bar-codes/edit/{barCode}', [App\Http\Controllers\CraftablePro\BarCodeController::class, 'edit'])->name('bar-codes.edit');
    Route::match(['put', 'patch'], 'bar-codes/{barCode}', [App\Http\Controllers\CraftablePro\BarCodeController::class, 'update'])->name('bar-codes.update');
    Route::delete('bar-codes/{barCode}', [App\Http\Controllers\CraftablePro\BarCodeController::class, 'destroy'])->name('bar-codes.destroy');
    Route::post('bar-codes/bulk-destroy', [App\Http\Controllers\CraftablePro\BarCodeController::class, 'bulkDestroy'])->name('bar-codes.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('prices', [App\Http\Controllers\CraftablePro\PriceController::class, 'index'])->name('prices.index');
    Route::get('prices/create', [App\Http\Controllers\CraftablePro\PriceController::class, 'create'])->name('prices.create');
    Route::post('prices', [App\Http\Controllers\CraftablePro\PriceController::class, 'store'])->name('prices.store');
    Route::get('prices/edit/{price}', [App\Http\Controllers\CraftablePro\PriceController::class, 'edit'])->name('prices.edit');
    Route::match(['put', 'patch'], 'prices/{price}', [App\Http\Controllers\CraftablePro\PriceController::class, 'update'])->name('prices.update');
    Route::delete('prices/{price}', [App\Http\Controllers\CraftablePro\PriceController::class, 'destroy'])->name('prices.destroy');
    Route::post('prices/bulk-destroy', [App\Http\Controllers\CraftablePro\PriceController::class, 'bulkDestroy'])->name('prices.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('supplier-tax-types', [App\Http\Controllers\CraftablePro\SupplierTaxTypeController::class, 'index'])->name('supplier-tax-types.index');
    Route::get('supplier-tax-types/create', [App\Http\Controllers\CraftablePro\SupplierTaxTypeController::class, 'create'])->name('supplier-tax-types.create');
    Route::post('supplier-tax-types', [App\Http\Controllers\CraftablePro\SupplierTaxTypeController::class, 'store'])->name('supplier-tax-types.store');
    Route::get('supplier-tax-types/edit/{supplierTaxType}', [App\Http\Controllers\CraftablePro\SupplierTaxTypeController::class, 'edit'])->name('supplier-tax-types.edit');
    Route::match(['put', 'patch'], 'supplier-tax-types/{supplierTaxType}', [App\Http\Controllers\CraftablePro\SupplierTaxTypeController::class, 'update'])->name('supplier-tax-types.update');
    Route::delete('supplier-tax-types/{supplierTaxType}', [App\Http\Controllers\CraftablePro\SupplierTaxTypeController::class, 'destroy'])->name('supplier-tax-types.destroy');
    Route::post('supplier-tax-types/bulk-destroy', [App\Http\Controllers\CraftablePro\SupplierTaxTypeController::class, 'bulkDestroy'])->name('supplier-tax-types.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('supplier-item-tax-types', [App\Http\Controllers\CraftablePro\SupplierItemTaxTypeController::class, 'index'])->name('supplier-item-tax-types.index');
    Route::get('supplier-item-tax-types/create', [App\Http\Controllers\CraftablePro\SupplierItemTaxTypeController::class, 'create'])->name('supplier-item-tax-types.create');
    Route::post('supplier-item-tax-types', [App\Http\Controllers\CraftablePro\SupplierItemTaxTypeController::class, 'store'])->name('supplier-item-tax-types.store');
    Route::get('supplier-item-tax-types/edit/{supplierItemTaxType}', [App\Http\Controllers\CraftablePro\SupplierItemTaxTypeController::class, 'edit'])->name('supplier-item-tax-types.edit');
    Route::match(['put', 'patch'], 'supplier-item-tax-types/{supplierItemTaxType}', [App\Http\Controllers\CraftablePro\SupplierItemTaxTypeController::class, 'update'])->name('supplier-item-tax-types.update');
    Route::delete('supplier-item-tax-types/{supplierItemTaxType}', [App\Http\Controllers\CraftablePro\SupplierItemTaxTypeController::class, 'destroy'])->name('supplier-item-tax-types.destroy');
    Route::post('supplier-item-tax-types/bulk-destroy', [App\Http\Controllers\CraftablePro\SupplierItemTaxTypeController::class, 'bulkDestroy'])->name('supplier-item-tax-types.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('discount-types', [App\Http\Controllers\CraftablePro\DiscountTypeController::class, 'index'])->name('discount-types.index');
    Route::get('discount-types/create', [App\Http\Controllers\CraftablePro\DiscountTypeController::class, 'create'])->name('discount-types.create');
    Route::post('discount-types', [App\Http\Controllers\CraftablePro\DiscountTypeController::class, 'store'])->name('discount-types.store');
    Route::get('discount-types/edit/{discountType}', [App\Http\Controllers\CraftablePro\DiscountTypeController::class, 'edit'])->name('discount-types.edit');
    Route::match(['put', 'patch'], 'discount-types/{discountType}', [App\Http\Controllers\CraftablePro\DiscountTypeController::class, 'update'])->name('discount-types.update');
    Route::delete('discount-types/{discountType}', [App\Http\Controllers\CraftablePro\DiscountTypeController::class, 'destroy'])->name('discount-types.destroy');
    Route::post('discount-types/bulk-destroy', [App\Http\Controllers\CraftablePro\DiscountTypeController::class, 'bulkDestroy'])->name('discount-types.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('discounts', [App\Http\Controllers\CraftablePro\DiscountController::class, 'index'])->name('discounts.index');
    Route::get('discounts/create', [App\Http\Controllers\CraftablePro\DiscountController::class, 'create'])->name('discounts.create');
    Route::post('discounts', [App\Http\Controllers\CraftablePro\DiscountController::class, 'store'])->name('discounts.store');
    Route::get('discounts/edit/{discount}', [App\Http\Controllers\CraftablePro\DiscountController::class, 'edit'])->name('discounts.edit');
    Route::match(['put', 'patch'], 'discounts/{discount}', [App\Http\Controllers\CraftablePro\DiscountController::class, 'update'])->name('discounts.update');
    Route::delete('discounts/{discount}', [App\Http\Controllers\CraftablePro\DiscountController::class, 'destroy'])->name('discounts.destroy');
    Route::post('discounts/bulk-destroy', [App\Http\Controllers\CraftablePro\DiscountController::class, 'bulkDestroy'])->name('discounts.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('payment-terms', [App\Http\Controllers\CraftablePro\PaymentTermController::class, 'index'])->name('payment-terms.index');
    Route::get('payment-terms/create', [App\Http\Controllers\CraftablePro\PaymentTermController::class, 'create'])->name('payment-terms.create');
    Route::post('payment-terms', [App\Http\Controllers\CraftablePro\PaymentTermController::class, 'store'])->name('payment-terms.store');
    Route::get('payment-terms/edit/{paymentTerm}', [App\Http\Controllers\CraftablePro\PaymentTermController::class, 'edit'])->name('payment-terms.edit');
    Route::match(['put', 'patch'], 'payment-terms/{paymentTerm}', [App\Http\Controllers\CraftablePro\PaymentTermController::class, 'update'])->name('payment-terms.update');
    Route::delete('payment-terms/{paymentTerm}', [App\Http\Controllers\CraftablePro\PaymentTermController::class, 'destroy'])->name('payment-terms.destroy');
    Route::post('payment-terms/bulk-destroy', [App\Http\Controllers\CraftablePro\PaymentTermController::class, 'bulkDestroy'])->name('payment-terms.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('order-headers', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'index'])->name('order-headers.index');
    Route::get('order-headers/create', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'create'])->name('order-headers.create');
    Route::post('order-headers', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'store'])->name('order-headers.store');
    Route::get('order-headers/edit/{orderHeader}', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'edit'])->name('order-headers.edit');
    Route::match(['put', 'patch'], 'order-headers/{orderHeader}', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'update'])->name('order-headers.update');
    Route::delete('order-headers/{orderHeader}', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'destroy'])->name('order-headers.destroy');
    Route::post('order-headers/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'bulkDestroy'])->name('order-headers.bulk-destroy');
    Route::post('order-headers/{orderHeader}/confirm', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'confirm'])->name('order-headers.confirm');
    Route::post('order-headers/{orderHeader}/cancel', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'cancel'])->name('order-headers.cancel');
    Route::post('order-headers/{orderHeader}/invoice', [App\Http\Controllers\CraftablePro\OrderHeaderController::class, 'invoice'])->name('order-headers.invoice');

    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('order-lines', [App\Http\Controllers\CraftablePro\OrderLineController::class, 'index'])->name('order-lines.index');
    Route::get('order-lines/create', [App\Http\Controllers\CraftablePro\OrderLineController::class, 'create'])->name('order-lines.create');
    Route::post('order-lines', [App\Http\Controllers\CraftablePro\OrderLineController::class, 'store'])->name('order-lines.store');
    Route::get('order-lines/edit/{orderLine}', [App\Http\Controllers\CraftablePro\OrderLineController::class, 'edit'])->name('order-lines.edit');
    Route::match(['put', 'patch'], 'order-lines/{orderLine}', [App\Http\Controllers\CraftablePro\OrderLineController::class, 'update'])->name('order-lines.update');
    Route::delete('order-lines/{orderLine}', [App\Http\Controllers\CraftablePro\OrderLineController::class, 'destroy'])->name('order-lines.destroy');
    Route::post('order-lines/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderLineController::class, 'bulkDestroy'])->name('order-lines.bulk-destroy');
    
});


/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('order-status-histories', [App\Http\Controllers\CraftablePro\OrderStatusHistoryController::class, 'index'])->name('order-status-histories.index');
    Route::get('order-status-histories/create', [App\Http\Controllers\CraftablePro\OrderStatusHistoryController::class, 'create'])->name('order-status-histories.create');
    Route::post('order-status-histories', [App\Http\Controllers\CraftablePro\OrderStatusHistoryController::class, 'store'])->name('order-status-histories.store');
    Route::get('order-status-histories/edit/{orderStatusHistory}', [App\Http\Controllers\CraftablePro\OrderStatusHistoryController::class, 'edit'])->name('order-status-histories.edit');
    Route::match(['put', 'patch'], 'order-status-histories/{orderStatusHistory}', [App\Http\Controllers\CraftablePro\OrderStatusHistoryController::class, 'update'])->name('order-status-histories.update');
    Route::delete('order-status-histories/{orderStatusHistory}', [App\Http\Controllers\CraftablePro\OrderStatusHistoryController::class, 'destroy'])->name('order-status-histories.destroy');
    Route::post('order-status-histories/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderStatusHistoryController::class, 'bulkDestroy'])->name('order-status-histories.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('holidays', [App\Http\Controllers\CraftablePro\HolidayController::class, 'index'])->name('holidays.index');
    Route::get('holidays/create', [App\Http\Controllers\CraftablePro\HolidayController::class, 'create'])->name('holidays.create');
    Route::post('holidays', [App\Http\Controllers\CraftablePro\HolidayController::class, 'store'])->name('holidays.store');
    Route::get('holidays/edit/{holiday}', [App\Http\Controllers\CraftablePro\HolidayController::class, 'edit'])->name('holidays.edit');
    Route::match(['put', 'patch'], 'holidays/{holiday}', [App\Http\Controllers\CraftablePro\HolidayController::class, 'update'])->name('holidays.update');
    Route::delete('holidays/{holiday}', [App\Http\Controllers\CraftablePro\HolidayController::class, 'destroy'])->name('holidays.destroy');
    Route::post('holidays/bulk-destroy', [App\Http\Controllers\CraftablePro\HolidayController::class, 'bulkDestroy'])->name('holidays.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('discount-schedules', [App\Http\Controllers\CraftablePro\DiscountScheduleController::class, 'index'])->name('discount-schedules.index');
    Route::get('discount-schedules/create', [App\Http\Controllers\CraftablePro\DiscountScheduleController::class, 'create'])->name('discount-schedules.create');
    Route::post('discount-schedules', [App\Http\Controllers\CraftablePro\DiscountScheduleController::class, 'store'])->name('discount-schedules.store');
    Route::get('discount-schedules/edit/{discountSchedule}', [App\Http\Controllers\CraftablePro\DiscountScheduleController::class, 'edit'])->name('discount-schedules.edit');
    Route::match(['put', 'patch'], 'discount-schedules/{discountSchedule}', [App\Http\Controllers\CraftablePro\DiscountScheduleController::class, 'update'])->name('discount-schedules.update');
    Route::delete('discount-schedules/{discountSchedule}', [App\Http\Controllers\CraftablePro\DiscountScheduleController::class, 'destroy'])->name('discount-schedules.destroy');
    Route::post('discount-schedules/bulk-destroy', [App\Http\Controllers\CraftablePro\DiscountScheduleController::class, 'bulkDestroy'])->name('discount-schedules.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('document-categories', [App\Http\Controllers\CraftablePro\DocumentCategoryController::class, 'index'])->name('document-categories.index');
    Route::get('document-categories/create', [App\Http\Controllers\CraftablePro\DocumentCategoryController::class, 'create'])->name('document-categories.create');
    Route::post('document-categories', [App\Http\Controllers\CraftablePro\DocumentCategoryController::class, 'store'])->name('document-categories.store');
    Route::get('document-categories/edit/{documentCategory}', [App\Http\Controllers\CraftablePro\DocumentCategoryController::class, 'edit'])->name('document-categories.edit');
    Route::match(['put', 'patch'], 'document-categories/{documentCategory}', [App\Http\Controllers\CraftablePro\DocumentCategoryController::class, 'update'])->name('document-categories.update');
    Route::delete('document-categories/{documentCategory}', [App\Http\Controllers\CraftablePro\DocumentCategoryController::class, 'destroy'])->name('document-categories.destroy');
    Route::post('document-categories/bulk-destroy', [App\Http\Controllers\CraftablePro\DocumentCategoryController::class, 'bulkDestroy'])->name('document-categories.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('document-types', [App\Http\Controllers\CraftablePro\DocumentTypeController::class, 'index'])->name('document-types.index');
    Route::get('document-types/create', [App\Http\Controllers\CraftablePro\DocumentTypeController::class, 'create'])->name('document-types.create');
    Route::post('document-types', [App\Http\Controllers\CraftablePro\DocumentTypeController::class, 'store'])->name('document-types.store');
    Route::get('document-types/edit/{documentType}', [App\Http\Controllers\CraftablePro\DocumentTypeController::class, 'edit'])->name('document-types.edit');
    Route::match(['put', 'patch'], 'document-types/{documentType}', [App\Http\Controllers\CraftablePro\DocumentTypeController::class, 'update'])->name('document-types.update');
    Route::delete('document-types/{documentType}', [App\Http\Controllers\CraftablePro\DocumentTypeController::class, 'destroy'])->name('document-types.destroy');
    Route::post('document-types/bulk-destroy', [App\Http\Controllers\CraftablePro\DocumentTypeController::class, 'bulkDestroy'])->name('document-types.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('item-tax-types', [App\Http\Controllers\CraftablePro\ItemTaxTypeController::class, 'index'])->name('item-tax-types.index');
    Route::get('item-tax-types/create', [App\Http\Controllers\CraftablePro\ItemTaxTypeController::class, 'create'])->name('item-tax-types.create');
    Route::post('item-tax-types', [App\Http\Controllers\CraftablePro\ItemTaxTypeController::class, 'store'])->name('item-tax-types.store');
    Route::get('item-tax-types/edit/{itemTaxType}', [App\Http\Controllers\CraftablePro\ItemTaxTypeController::class, 'edit'])->name('item-tax-types.edit');
    Route::match(['put', 'patch'], 'item-tax-types/{itemTaxType}', [App\Http\Controllers\CraftablePro\ItemTaxTypeController::class, 'update'])->name('item-tax-types.update');
    Route::delete('item-tax-types/{itemTaxType}', [App\Http\Controllers\CraftablePro\ItemTaxTypeController::class, 'destroy'])->name('item-tax-types.destroy');
    Route::post('item-tax-types/bulk-destroy', [App\Http\Controllers\CraftablePro\ItemTaxTypeController::class, 'bulkDestroy'])->name('item-tax-types.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('shipments', [App\Http\Controllers\CraftablePro\ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('shipments/create', [App\Http\Controllers\CraftablePro\ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('shipments', [App\Http\Controllers\CraftablePro\ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('shipments/edit/{shipment}', [App\Http\Controllers\CraftablePro\ShipmentController::class, 'edit'])->name('shipments.edit');
    Route::match(['put', 'patch'], 'shipments/{shipment}', [App\Http\Controllers\CraftablePro\ShipmentController::class, 'update'])->name('shipments.update');
    Route::delete('shipments/{shipment}', [App\Http\Controllers\CraftablePro\ShipmentController::class, 'destroy'])->name('shipments.destroy');
    Route::post('shipments/bulk-destroy', [App\Http\Controllers\CraftablePro\ShipmentController::class, 'bulkDestroy'])->name('shipments.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('order-reviews', [App\Http\Controllers\CraftablePro\OrderReviewController::class, 'index'])->name('order-reviews.index');
    Route::get('order-reviews/create', [App\Http\Controllers\CraftablePro\OrderReviewController::class, 'create'])->name('order-reviews.create');
    Route::post('order-reviews', [App\Http\Controllers\CraftablePro\OrderReviewController::class, 'store'])->name('order-reviews.store');
    Route::get('order-reviews/edit/{orderReview}', [App\Http\Controllers\CraftablePro\OrderReviewController::class, 'edit'])->name('order-reviews.edit');
    Route::match(['put', 'patch'], 'order-reviews/{orderReview}', [App\Http\Controllers\CraftablePro\OrderReviewController::class, 'update'])->name('order-reviews.update');
    Route::delete('order-reviews/{orderReview}', [App\Http\Controllers\CraftablePro\OrderReviewController::class, 'destroy'])->name('order-reviews.destroy');
    Route::post('order-reviews/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderReviewController::class, 'bulkDestroy'])->name('order-reviews.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('order-discounts', [App\Http\Controllers\CraftablePro\OrderDiscountController::class, 'index'])->name('order-discounts.index');
    Route::get('order-discounts/create', [App\Http\Controllers\CraftablePro\OrderDiscountController::class, 'create'])->name('order-discounts.create');
    Route::post('order-discounts', [App\Http\Controllers\CraftablePro\OrderDiscountController::class, 'store'])->name('order-discounts.store');
    Route::get('order-discounts/edit/{orderDiscount}', [App\Http\Controllers\CraftablePro\OrderDiscountController::class, 'edit'])->name('order-discounts.edit');
    Route::match(['put', 'patch'], 'order-discounts/{orderDiscount}', [App\Http\Controllers\CraftablePro\OrderDiscountController::class, 'update'])->name('order-discounts.update');
    Route::delete('order-discounts/{orderDiscount}', [App\Http\Controllers\CraftablePro\OrderDiscountController::class, 'destroy'])->name('order-discounts.destroy');
    Route::post('order-discounts/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderDiscountController::class, 'bulkDestroy'])->name('order-discounts.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('order-line-discounts', [App\Http\Controllers\CraftablePro\OrderLineDiscountController::class, 'index'])->name('order-line-discounts.index');
    Route::get('order-line-discounts/create', [App\Http\Controllers\CraftablePro\OrderLineDiscountController::class, 'create'])->name('order-line-discounts.create');
    Route::post('order-line-discounts', [App\Http\Controllers\CraftablePro\OrderLineDiscountController::class, 'store'])->name('order-line-discounts.store');
    Route::get('order-line-discounts/edit/{orderLineDiscount}', [App\Http\Controllers\CraftablePro\OrderLineDiscountController::class, 'edit'])->name('order-line-discounts.edit');
    Route::match(['put', 'patch'], 'order-line-discounts/{orderLineDiscount}', [App\Http\Controllers\CraftablePro\OrderLineDiscountController::class, 'update'])->name('order-line-discounts.update');
    Route::delete('order-line-discounts/{orderLineDiscount}', [App\Http\Controllers\CraftablePro\OrderLineDiscountController::class, 'destroy'])->name('order-line-discounts.destroy');
    Route::post('order-line-discounts/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderLineDiscountController::class, 'bulkDestroy'])->name('order-line-discounts.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('purchases', [App\Http\Controllers\CraftablePro\PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/create', [App\Http\Controllers\CraftablePro\PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('purchases', [App\Http\Controllers\CraftablePro\PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('purchases/edit/{purchase}', [App\Http\Controllers\CraftablePro\PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::match(['put', 'patch'], 'purchases/{purchase}', [App\Http\Controllers\CraftablePro\PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('purchases/{purchase}', [App\Http\Controllers\CraftablePro\PurchaseController::class, 'destroy'])->name('purchases.destroy');
    Route::post('purchases/bulk-destroy', [App\Http\Controllers\CraftablePro\PurchaseController::class, 'bulkDestroy'])->name('purchases.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('purchase-items', [App\Http\Controllers\CraftablePro\PurchaseItemController::class, 'index'])->name('purchase-items.index');
    Route::get('purchase-items/create', [App\Http\Controllers\CraftablePro\PurchaseItemController::class, 'create'])->name('purchase-items.create');
    Route::post('purchase-items', [App\Http\Controllers\CraftablePro\PurchaseItemController::class, 'store'])->name('purchase-items.store');
    Route::get('purchase-items/edit/{purchaseItem}', [App\Http\Controllers\CraftablePro\PurchaseItemController::class, 'edit'])->name('purchase-items.edit');
    Route::match(['put', 'patch'], 'purchase-items/{purchaseItem}', [App\Http\Controllers\CraftablePro\PurchaseItemController::class, 'update'])->name('purchase-items.update');
    Route::delete('purchase-items/{purchaseItem}', [App\Http\Controllers\CraftablePro\PurchaseItemController::class, 'destroy'])->name('purchase-items.destroy');
    Route::post('purchase-items/bulk-destroy', [App\Http\Controllers\CraftablePro\PurchaseItemController::class, 'bulkDestroy'])->name('purchase-items.bulk-destroy');
    Route::post('purchases/{purchase}/receive', [App\Http\Controllers\CraftablePro\PurchaseController::class, 'receive'])->name('purchases.receive');

    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('inventory-counts', [App\Http\Controllers\CraftablePro\InventoryCountController::class, 'index'])->name('inventory-counts.index');
    Route::get('inventory-counts/create', [App\Http\Controllers\CraftablePro\InventoryCountController::class, 'create'])->name('inventory-counts.create');
    Route::post('inventory-counts', [App\Http\Controllers\CraftablePro\InventoryCountController::class, 'store'])->name('inventory-counts.store');
    Route::get('inventory-counts/edit/{inventoryCount}', [App\Http\Controllers\CraftablePro\InventoryCountController::class, 'edit'])->name('inventory-counts.edit');
    Route::match(['put', 'patch'], 'inventory-counts/{inventoryCount}', [App\Http\Controllers\CraftablePro\InventoryCountController::class, 'update'])->name('inventory-counts.update');
    Route::delete('inventory-counts/{inventoryCount}', [App\Http\Controllers\CraftablePro\InventoryCountController::class, 'destroy'])->name('inventory-counts.destroy');
    Route::post('inventory-counts/bulk-destroy', [App\Http\Controllers\CraftablePro\InventoryCountController::class, 'bulkDestroy'])->name('inventory-counts.bulk-destroy');
    Route::post('inventory-counts/{inventoryCount}/apply', [App\Http\Controllers\CraftablePro\InventoryCountController::class, 'apply'])->name('inventory-counts.apply');

    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('inventory-count-items', [App\Http\Controllers\CraftablePro\InventoryCountItemController::class, 'index'])->name('inventory-count-items.index');
    Route::get('inventory-count-items/create', [App\Http\Controllers\CraftablePro\InventoryCountItemController::class, 'create'])->name('inventory-count-items.create');
    Route::post('inventory-count-items', [App\Http\Controllers\CraftablePro\InventoryCountItemController::class, 'store'])->name('inventory-count-items.store');
    Route::get('inventory-count-items/edit/{inventoryCountItem}', [App\Http\Controllers\CraftablePro\InventoryCountItemController::class, 'edit'])->name('inventory-count-items.edit');
    Route::match(['put', 'patch'], 'inventory-count-items/{inventoryCountItem}', [App\Http\Controllers\CraftablePro\InventoryCountItemController::class, 'update'])->name('inventory-count-items.update');
    Route::delete('inventory-count-items/{inventoryCountItem}', [App\Http\Controllers\CraftablePro\InventoryCountItemController::class, 'destroy'])->name('inventory-count-items.destroy');
    Route::post('inventory-count-items/bulk-destroy', [App\Http\Controllers\CraftablePro\InventoryCountItemController::class, 'bulkDestroy'])->name('inventory-count-items.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('loss-and-damages', [App\Http\Controllers\CraftablePro\LossAndDamageController::class, 'index'])->name('loss-and-damages.index');
    Route::get('loss-and-damages/create', [App\Http\Controllers\CraftablePro\LossAndDamageController::class, 'create'])->name('loss-and-damages.create');
    Route::post('loss-and-damages', [App\Http\Controllers\CraftablePro\LossAndDamageController::class, 'store'])->name('loss-and-damages.store');
    Route::get('loss-and-damages/edit/{lossAndDamage}', [App\Http\Controllers\CraftablePro\LossAndDamageController::class, 'edit'])->name('loss-and-damages.edit');
    Route::match(['put', 'patch'], 'loss-and-damages/{lossAndDamage}', [App\Http\Controllers\CraftablePro\LossAndDamageController::class, 'update'])->name('loss-and-damages.update');
    Route::delete('loss-and-damages/{lossAndDamage}', [App\Http\Controllers\CraftablePro\LossAndDamageController::class, 'destroy'])->name('loss-and-damages.destroy');
    Route::post('loss-and-damages/bulk-destroy', [App\Http\Controllers\CraftablePro\LossAndDamageController::class, 'bulkDestroy'])->name('loss-and-damages.bulk-destroy');
    Route::post('loss-and-damages/{lossAndDamage}/apply', [App\Http\Controllers\CraftablePro\LossAndDamageController::class, 'apply'])->name('loss-and-damages.apply');

    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('loss-and-damage-items', [App\Http\Controllers\CraftablePro\LossAndDamageItemController::class, 'index'])->name('loss-and-damage-items.index');
    Route::get('loss-and-damage-items/create', [App\Http\Controllers\CraftablePro\LossAndDamageItemController::class, 'create'])->name('loss-and-damage-items.create');
    Route::post('loss-and-damage-items', [App\Http\Controllers\CraftablePro\LossAndDamageItemController::class, 'store'])->name('loss-and-damage-items.store');
    Route::get('loss-and-damage-items/edit/{lossAndDamageItem}', [App\Http\Controllers\CraftablePro\LossAndDamageItemController::class, 'edit'])->name('loss-and-damage-items.edit');
    Route::match(['put', 'patch'], 'loss-and-damage-items/{lossAndDamageItem}', [App\Http\Controllers\CraftablePro\LossAndDamageItemController::class, 'update'])->name('loss-and-damage-items.update');
    Route::delete('loss-and-damage-items/{lossAndDamageItem}', [App\Http\Controllers\CraftablePro\LossAndDamageItemController::class, 'destroy'])->name('loss-and-damage-items.destroy');
    Route::post('loss-and-damage-items/bulk-destroy', [App\Http\Controllers\CraftablePro\LossAndDamageItemController::class, 'bulkDestroy'])->name('loss-and-damage-items.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('stock-returns', [App\Http\Controllers\CraftablePro\StockReturnController::class, 'index'])->name('stock-returns.index');
    Route::get('stock-returns/create', [App\Http\Controllers\CraftablePro\StockReturnController::class, 'create'])->name('stock-returns.create');
    Route::post('stock-returns', [App\Http\Controllers\CraftablePro\StockReturnController::class, 'store'])->name('stock-returns.store');
    Route::get('stock-returns/edit/{stockReturn}', [App\Http\Controllers\CraftablePro\StockReturnController::class, 'edit'])->name('stock-returns.edit');
    Route::match(['put', 'patch'], 'stock-returns/{stockReturn}', [App\Http\Controllers\CraftablePro\StockReturnController::class, 'update'])->name('stock-returns.update');
    Route::delete('stock-returns/{stockReturn}', [App\Http\Controllers\CraftablePro\StockReturnController::class, 'destroy'])->name('stock-returns.destroy');
    Route::post('stock-returns/bulk-destroy', [App\Http\Controllers\CraftablePro\StockReturnController::class, 'bulkDestroy'])->name('stock-returns.bulk-destroy');
    Route::post('stock-returns/{stockReturn}/process', [App\Http\Controllers\CraftablePro\StockReturnController::class, 'process'])->name('stock-returns.process');

});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('stock-return-items', [App\Http\Controllers\CraftablePro\StockReturnItemController::class, 'index'])->name('stock-return-items.index');
    Route::get('stock-return-items/create', [App\Http\Controllers\CraftablePro\StockReturnItemController::class, 'create'])->name('stock-return-items.create');
    Route::post('stock-return-items', [App\Http\Controllers\CraftablePro\StockReturnItemController::class, 'store'])->name('stock-return-items.store');
    Route::get('stock-return-items/edit/{stockReturnItem}', [App\Http\Controllers\CraftablePro\StockReturnItemController::class, 'edit'])->name('stock-return-items.edit');
    Route::match(['put', 'patch'], 'stock-return-items/{stockReturnItem}', [App\Http\Controllers\CraftablePro\StockReturnItemController::class, 'update'])->name('stock-return-items.update');
    Route::delete('stock-return-items/{stockReturnItem}', [App\Http\Controllers\CraftablePro\StockReturnItemController::class, 'destroy'])->name('stock-return-items.destroy');
    Route::post('stock-return-items/bulk-destroy', [App\Http\Controllers\CraftablePro\StockReturnItemController::class, 'bulkDestroy'])->name('stock-return-items.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('documents', [App\Http\Controllers\CraftablePro\DocumentController::class, 'index'])->name('documents.index');
    Route::get('documents/create', [App\Http\Controllers\CraftablePro\DocumentController::class, 'create'])->name('documents.create');
    Route::post('documents', [App\Http\Controllers\CraftablePro\DocumentController::class, 'store'])->name('documents.store');
    Route::get('documents/edit/{document}', [App\Http\Controllers\CraftablePro\DocumentController::class, 'edit'])->name('documents.edit');
    Route::match(['put', 'patch'], 'documents/{document}', [App\Http\Controllers\CraftablePro\DocumentController::class, 'update'])->name('documents.update');
    Route::delete('documents/{document}', [App\Http\Controllers\CraftablePro\DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('documents/bulk-destroy', [App\Http\Controllers\CraftablePro\DocumentController::class, 'bulkDestroy'])->name('documents.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('stock-histories', [App\Http\Controllers\CraftablePro\StockHistoryController::class, 'index'])->name('stock-histories.index');
    Route::get('stock-histories/create', [App\Http\Controllers\CraftablePro\StockHistoryController::class, 'create'])->name('stock-histories.create');
    Route::post('stock-histories', [App\Http\Controllers\CraftablePro\StockHistoryController::class, 'store'])->name('stock-histories.store');
    Route::get('stock-histories/edit/{stockHistory}', [App\Http\Controllers\CraftablePro\StockHistoryController::class, 'edit'])->name('stock-histories.edit');
    Route::match(['put', 'patch'], 'stock-histories/{stockHistory}', [App\Http\Controllers\CraftablePro\StockHistoryController::class, 'update'])->name('stock-histories.update');
    Route::delete('stock-histories/{stockHistory}', [App\Http\Controllers\CraftablePro\StockHistoryController::class, 'destroy'])->name('stock-histories.destroy');
    Route::post('stock-histories/bulk-destroy', [App\Http\Controllers\CraftablePro\StockHistoryController::class, 'bulkDestroy'])->name('stock-histories.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('invoices', [App\Http\Controllers\CraftablePro\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [App\Http\Controllers\CraftablePro\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices', [App\Http\Controllers\CraftablePro\InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/edit/{invoice}', [App\Http\Controllers\CraftablePro\InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::match(['put', 'patch'], 'invoices/{invoice}', [App\Http\Controllers\CraftablePro\InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('invoices/{invoice}', [App\Http\Controllers\CraftablePro\InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::post('invoices/bulk-destroy', [App\Http\Controllers\CraftablePro\InvoiceController::class, 'bulkDestroy'])->name('invoices.bulk-destroy');
    Route::post('invoices/{invoice}/pay', [App\Http\Controllers\CraftablePro\InvoiceController::class, 'pay'])->name('invoices.pay');

    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('invoice-lines', [App\Http\Controllers\CraftablePro\InvoiceLineController::class, 'index'])->name('invoice-lines.index');
    Route::get('invoice-lines/create', [App\Http\Controllers\CraftablePro\InvoiceLineController::class, 'create'])->name('invoice-lines.create');
    Route::post('invoice-lines', [App\Http\Controllers\CraftablePro\InvoiceLineController::class, 'store'])->name('invoice-lines.store');
    Route::get('invoice-lines/edit/{invoiceLine}', [App\Http\Controllers\CraftablePro\InvoiceLineController::class, 'edit'])->name('invoice-lines.edit');
    Route::match(['put', 'patch'], 'invoice-lines/{invoiceLine}', [App\Http\Controllers\CraftablePro\InvoiceLineController::class, 'update'])->name('invoice-lines.update');
    Route::delete('invoice-lines/{invoiceLine}', [App\Http\Controllers\CraftablePro\InvoiceLineController::class, 'destroy'])->name('invoice-lines.destroy');
    Route::post('invoice-lines/bulk-destroy', [App\Http\Controllers\CraftablePro\InvoiceLineController::class, 'bulkDestroy'])->name('invoice-lines.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('payments', [App\Http\Controllers\CraftablePro\PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [App\Http\Controllers\CraftablePro\PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [App\Http\Controllers\CraftablePro\PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/edit/{payment}', [App\Http\Controllers\CraftablePro\PaymentController::class, 'edit'])->name('payments.edit');
    Route::match(['put', 'patch'], 'payments/{payment}', [App\Http\Controllers\CraftablePro\PaymentController::class, 'update'])->name('payments.update');
    Route::delete('payments/{payment}', [App\Http\Controllers\CraftablePro\PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('payments/bulk-destroy', [App\Http\Controllers\CraftablePro\PaymentController::class, 'bulkDestroy'])->name('payments.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('sale-returns', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'index'])->name('sale-returns.index');
    Route::get('sale-returns/create', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'create'])->name('sale-returns.create');
    Route::post('sale-returns', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'store'])->name('sale-returns.store');
    Route::get('sale-returns/edit/{saleReturn}', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'edit'])->name('sale-returns.edit');
    Route::match(['put', 'patch'], 'sale-returns/{saleReturn}', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'update'])->name('sale-returns.update');
    Route::delete('sale-returns/{saleReturn}', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'destroy'])->name('sale-returns.destroy');
    Route::post('sale-returns/bulk-destroy', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'bulkDestroy'])->name('sale-returns.bulk-destroy');
    Route::post('sale-returns/{saleReturn}/process', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'process'])->name('sale-returns.process');
    Route::post('sale-returns/{saleReturn}/refund', [App\Http\Controllers\CraftablePro\SaleReturnController::class, 'refund'])->name('sale-returns.refund');

    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('sale-return-items', [App\Http\Controllers\CraftablePro\SaleReturnItemController::class, 'index'])->name('sale-return-items.index');
    Route::get('sale-return-items/create', [App\Http\Controllers\CraftablePro\SaleReturnItemController::class, 'create'])->name('sale-return-items.create');
    Route::post('sale-return-items', [App\Http\Controllers\CraftablePro\SaleReturnItemController::class, 'store'])->name('sale-return-items.store');
    Route::get('sale-return-items/edit/{saleReturnItem}', [App\Http\Controllers\CraftablePro\SaleReturnItemController::class, 'edit'])->name('sale-return-items.edit');
    Route::match(['put', 'patch'], 'sale-return-items/{saleReturnItem}', [App\Http\Controllers\CraftablePro\SaleReturnItemController::class, 'update'])->name('sale-return-items.update');
    Route::delete('sale-return-items/{saleReturnItem}', [App\Http\Controllers\CraftablePro\SaleReturnItemController::class, 'destroy'])->name('sale-return-items.destroy');
    Route::post('sale-return-items/bulk-destroy', [App\Http\Controllers\CraftablePro\SaleReturnItemController::class, 'bulkDestroy'])->name('sale-return-items.bulk-destroy');
    
});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('refunds', [App\Http\Controllers\CraftablePro\RefundController::class, 'index'])->name('refunds.index');
    Route::get('refunds/create', [App\Http\Controllers\CraftablePro\RefundController::class, 'create'])->name('refunds.create');
    Route::post('refunds', [App\Http\Controllers\CraftablePro\RefundController::class, 'store'])->name('refunds.store');
    Route::get('refunds/edit/{refund}', [App\Http\Controllers\CraftablePro\RefundController::class, 'edit'])->name('refunds.edit');
    Route::match(['put', 'patch'], 'refunds/{refund}', [App\Http\Controllers\CraftablePro\RefundController::class, 'update'])->name('refunds.update');
    Route::delete('refunds/{refund}', [App\Http\Controllers\CraftablePro\RefundController::class, 'destroy'])->name('refunds.destroy');
    Route::post('refunds/bulk-destroy', [App\Http\Controllers\CraftablePro\RefundController::class, 'bulkDestroy'])->name('refunds.bulk-destroy');
    
});
