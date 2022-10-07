<?php

namespace App\Http\Controllers\User;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShowCategoriesController extends Controller
{
    public function ShowCategory(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_category" => ["required","numeric",Rule::exists("categories","id")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $category = Category::queryCategoriesCountChildAndArticle($request)
                ->where("categories_Child.id",$request->id_category)->first();
            return Application::getApp()->getHandleJson()
                ->DataHandle(CategoriesResource::make($category),"category");
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function SearchCategories(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_category" => ["nullable","numeric",Rule::exists("categories","id")],
                "name" => ["nullable","string"]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $categories = Category::queryCategoriesCountChildAndArticle($request,true);
            if(!is_null($request->id_category)){
                $categories = $categories->where("categories_Child.id_parent",$request->id_category);
            }
            if(!is_null($request->name)){
                $categories = $categories->where(function ($query) use ($request){
                    $query->where("categories_Child.name","like",'%'.$request->name.'%')
                        ->orwhere("categories_Child.name_en","like",'%'.$request->name.'%');
                });
            }
            $finalData = $categories->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
            return Application::getApp()->getHandleJson()
                ->PaginateHandle("categories",CategoriesResource::collection($finalData));
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
