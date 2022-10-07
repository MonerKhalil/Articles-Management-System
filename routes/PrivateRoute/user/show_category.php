<?php

use App\Http\Controllers\User\ShowCategoriesController;
use Illuminate\Support\Facades\Route;


Route::controller(ShowCategoriesController::class)->prefix("category")
    ->group(function (){
        Route::get("show","ShowCategory");
        Route::get("search","SearchCategories");
});
