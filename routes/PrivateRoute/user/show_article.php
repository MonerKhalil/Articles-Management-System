<?php

use App\Http\Controllers\User\Article\ArticlesController;
use Illuminate\Support\Facades\Route;


Route::controller(ArticlesController::class)->prefix("article")
    ->group(function (){
        Route::get("search","SearchArticle");
        Route::get("show","ShowArticle");
});
