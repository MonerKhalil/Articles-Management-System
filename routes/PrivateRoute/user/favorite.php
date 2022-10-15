<?php

use App\Http\Controllers\User\Article\FavoriteController;
use Illuminate\Support\Facades\Route;


Route::controller(FavoriteController::class)->prefix("favorite")
    ->group(function (){
        Route::get("show","ShowArticlesFavorite");
        Route::post("toggle","AddOrRemoveFavorite");
});
