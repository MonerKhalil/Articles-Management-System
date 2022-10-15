<?php

use App\Http\Controllers\User\CategoriesController;
use Illuminate\Support\Facades\Route;


Route::controller(CategoriesController::class)->prefix("category")
    ->group(function (){
        Route::get("parent/show","ShowParentAndBrothersCategory");
        Route::get("search","SearchCategories");
});
