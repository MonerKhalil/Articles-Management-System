<?php

use \Illuminate\Support\Facades\Route;
use \App\Http\Controllers\User\AuthController;
use \App\Http\Controllers\User\ForgetPasswordController;


const Singn = "singe-";
Route::controller(AuthController::class)->group(function (){
    Route::prefix("auth")->group(function (){
        Route::post(Singn."in","SingeIn");//
        Route::post(Singn."up","SingeUp");//
        Route::delete(Singn."out","SingeOut");//
    });
    Route::post("email/active","CheckCode_ActiveEmail");//
    Route::post("send-code/email/active","SendCodeIfNotActive");//
});

Route::controller(ForgetPasswordController::class)
    ->prefix("forgot/password")->group(function (){
        Route::post("code","InputEmail_GenerateCode");//
        Route::post("check","CheckCodeAndCreateNewToken");//
        Route::put("new","CreateNewPassword");//
});
