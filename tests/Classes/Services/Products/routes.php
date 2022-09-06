<?php

use Illuminate\Support\Facades\Route;
use KEERill\ServiceStructure\Tests\Classes\Services\Products\Interfaces\Http\Controllers\ProductsController;

Route::prefix('products')
    ->group(function() {
        Route::get('{productId}', [ProductsController::class, 'get']);
    });
