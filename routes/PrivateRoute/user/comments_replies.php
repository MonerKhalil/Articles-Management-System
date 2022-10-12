<?php

use \Illuminate\Support\Facades\Route;
use \App\Http\Controllers\User\Article\CommentsArticleController;

Route::controller(CommentsArticleController::class)->group(function (){
    Route::prefix("comments")->group(function (){
        Route::get("show","ShowCommentsArticle");
    });
    Route::prefix("replies")->group(function (){
        Route::get("show","ShowRepliesComment");
    });
});
