<?php

namespace App\Http\Controllers\User;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowCategoriesController extends Controller
{
    public function ShowAllCategories(Request $request): JsonResponse
    {
        try {
            $lang = null;
            if(Application::getApp()->getLang()==="ar"){
                $lang = "";
            }
            else{
                $lang = "_en";
            }
            $categories = DB::table("categories")->select(["id","id_parent","path_photo",
                "name".$lang,"description".$lang
            ])->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
            return Application::getApp()->getHandleJson()->PaginateHandle("categories",$categories);
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
