<?php

use App\Http\Controllers\User\ShowCategoriesController;
use Illuminate\Support\Facades\Route;


Route::controller(ShowCategoriesController::class)->prefix("categories")
    ->group(function (){
        Route::get("show/all","");
});
