<?php

use \Illuminate\Support\Facades\Route;
use \App\Http\Controllers\User\Article\CommentsArticleController;

Route::controller(CommentsArticleController::class)->group(function (){
    Route::prefix("comment")->group(function (){
        Route::get("show","ShowCommentsArticle");
        Route::post("add","AddComment");
    });
    Route::prefix("reply")->group(function (){
        Route::get("show","ShowRepliesComment");
        Route::post("add","AddReply");
    });
});
