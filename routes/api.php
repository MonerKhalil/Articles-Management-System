<?php

use App\Http\Controllers\User\Article\ArticlesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;



Route::get("test",[ArticlesController::class,"ArticleCategories"]);

Route::get("http",function (Request $request){
//    dd($request->getClientIp());
    return [$request->getClientIp(),"mkdmksd","sakksamks"];
});



//Broadcast::routes(['prefix' => 'api','middleware' => ["api",'auth:user']]);

require __DIR__ . "/PrivateRoute/user/RouteUser.php";
