<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::craftablePro('admin');



/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('products', [App\Http\Controllers\CraftablePro\ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [App\Http\Controllers\CraftablePro\ProductController::class, 'create'])->name('products.create');
    Route::post('products', [App\Http\Controllers\CraftablePro\ProductController::class, 'store'])->name('products.store');
    Route::get('products/edit/{product}', [App\Http\Controllers\CraftablePro\ProductController::class, 'edit'])->name('products.edit');
    Route::match(['put', 'patch'], 'products/{product}', [App\Http\Controllers\CraftablePro\ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [App\Http\Controllers\CraftablePro\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('products/bulk-destroy', [App\Http\Controllers\CraftablePro\ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
    Route::get('products/export', [App\Http\Controllers\CraftablePro\ProductController::class, 'export'])->name('products.export');

});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('orders', [App\Http\Controllers\CraftablePro\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', [App\Http\Controllers\CraftablePro\OrderController::class, 'create'])->name('orders.create');
    Route::post('orders', [App\Http\Controllers\CraftablePro\OrderController::class, 'store'])->name('orders.store');
    Route::get('orders/edit/{order}', [App\Http\Controllers\CraftablePro\OrderController::class, 'edit'])->name('orders.edit');
    Route::match(['put', 'patch'], 'orders/{order}', [App\Http\Controllers\CraftablePro\OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [App\Http\Controllers\CraftablePro\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('orders/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderController::class, 'bulkDestroy'])->name('orders.bulk-destroy');
    Route::get('orders/export', [App\Http\Controllers\CraftablePro\OrderController::class, 'export'])->name('orders.export');

});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('order-items', [App\Http\Controllers\CraftablePro\OrderItemController::class, 'index'])->name('order-items.index');
    Route::get('order-items/create', [App\Http\Controllers\CraftablePro\OrderItemController::class, 'create'])->name('order-items.create');
    Route::post('order-items', [App\Http\Controllers\CraftablePro\OrderItemController::class, 'store'])->name('order-items.store');
    Route::get('order-items/edit/{orderItem}', [App\Http\Controllers\CraftablePro\OrderItemController::class, 'edit'])->name('order-items.edit');
    Route::match(['put', 'patch'], 'order-items/{orderItem}', [App\Http\Controllers\CraftablePro\OrderItemController::class, 'update'])->name('order-items.update');
    Route::delete('order-items/{orderItem}', [App\Http\Controllers\CraftablePro\OrderItemController::class, 'destroy'])->name('order-items.destroy');
    Route::post('order-items/bulk-destroy', [App\Http\Controllers\CraftablePro\OrderItemController::class, 'bulkDestroy'])->name('order-items.bulk-destroy');
    Route::get('order-items/export', [App\Http\Controllers\CraftablePro\OrderItemController::class, 'export'])->name('order-items.export');

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
    Route::get('payments/export', [App\Http\Controllers\CraftablePro\PaymentController::class, 'export'])->name('payments.export');

});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('carts', [App\Http\Controllers\CraftablePro\CartController::class, 'index'])->name('carts.index');
    Route::get('carts/create', [App\Http\Controllers\CraftablePro\CartController::class, 'create'])->name('carts.create');
    Route::post('carts', [App\Http\Controllers\CraftablePro\CartController::class, 'store'])->name('carts.store');
    Route::get('carts/edit/{cart}', [App\Http\Controllers\CraftablePro\CartController::class, 'edit'])->name('carts.edit');
    Route::match(['put', 'patch'], 'carts/{cart}', [App\Http\Controllers\CraftablePro\CartController::class, 'update'])->name('carts.update');
    Route::delete('carts/{cart}', [App\Http\Controllers\CraftablePro\CartController::class, 'destroy'])->name('carts.destroy');
    Route::post('carts/bulk-destroy', [App\Http\Controllers\CraftablePro\CartController::class, 'bulkDestroy'])->name('carts.bulk-destroy');
    Route::get('carts/export', [App\Http\Controllers\CraftablePro\CartController::class, 'export'])->name('carts.export');

});




/* Auto-generated admin routes */
Route::middleware('craftable-pro-middlewares')->prefix('admin')->name('craftable-pro.')->group(function () {
    Route::get('cart-items', [App\Http\Controllers\CraftablePro\CartItemController::class, 'index'])->name('cart-items.index');
    Route::get('cart-items/create', [App\Http\Controllers\CraftablePro\CartItemController::class, 'create'])->name('cart-items.create');
    Route::post('cart-items', [App\Http\Controllers\CraftablePro\CartItemController::class, 'store'])->name('cart-items.store');
    Route::get('cart-items/edit/{cartItem}', [App\Http\Controllers\CraftablePro\CartItemController::class, 'edit'])->name('cart-items.edit');
    Route::match(['put', 'patch'], 'cart-items/{cartItem}', [App\Http\Controllers\CraftablePro\CartItemController::class, 'update'])->name('cart-items.update');
    Route::delete('cart-items/{cartItem}', [App\Http\Controllers\CraftablePro\CartItemController::class, 'destroy'])->name('cart-items.destroy');
    Route::post('cart-items/bulk-destroy', [App\Http\Controllers\CraftablePro\CartItemController::class, 'bulkDestroy'])->name('cart-items.bulk-destroy');
    Route::get('cart-items/export', [App\Http\Controllers\CraftablePro\CartItemController::class, 'export'])->name('cart-items.export');

});
