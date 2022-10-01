<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get("Test1",[\App\Http\Controllers\TestController::class,"Test"]);

Route::get("Test",function (){
    return response()->view("test");
});

require __DIR__ . "\PrivateRoute\user\RouteUser.php";
