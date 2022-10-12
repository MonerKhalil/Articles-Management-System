<?php

use App\Http\Controllers\User\Article\ArticlesController;
use App\Http\Controllers\User\Article\CommentsArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get("test",[ArticlesController::class,"ArticleCategories"]);

Route::get("http",[ArticlesController::class,"ShowArticle"]);


require __DIR__ . "/PrivateRoute/user/RouteUser.php";
