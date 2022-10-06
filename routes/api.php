<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get("Test1",[\App\Http\Controllers\TestController::class,"Test"]);



require __DIR__ . "\PrivateRoute\user\RouteUser.php";
