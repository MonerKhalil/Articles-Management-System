<?php

use App\Http\Controllers\User\ShowCategoriesController;
use Illuminate\Support\Facades\Route;


Route::controller(ShowCategoriesController::class)->prefix("category")
    ->group(function (){
        Route::prefix("show")->group(function (){
            Route::get("","ShowCategory");
            Route::get("child","ShowChildCategory");
            Route::get("all","ShowAllCategories");
        });
});
