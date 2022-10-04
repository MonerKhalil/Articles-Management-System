<?php

use \Illuminate\Support\Facades\Route;
use \App\Http\Controllers\User\ProfileController;

Route::controller(ProfileController::class)
    ->prefix("profile")->group(function (){
        Route::get("show","Show");//
        Route::put("edit","Update");//
        Route::put("edit/password","UpdatePassword");//
        Route::put("edit/email","SendCodeUpdateEmail");//2
        Route::delete("clear","ClearPhonePhoto");
        Route::delete("destroy","Delete");//
});
