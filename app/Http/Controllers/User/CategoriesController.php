<?php

namespace App\Http\Controllers\User;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{

    public function ShowParentAndBrothersCategory(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_category" => ["required","numeric",Rule::exists("categories","id")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $Data = new class{};
            $category = Category::queryCategoriesCountChildAndArticle($request)
                ->where("categories_final.id",$request->id_category)->first();
            if(is_null($category)){
                Throw new \Exception(Application::getApp()->getErrorMessages()["id_category.exists"]);
            }
            $brothers = Category::queryCategoriesCountChildAndArticle($request)
                ->where("categories_final.id_parent",$category->id_parent??null)
                ->get();
            if (!is_null($category->id_parent)){
                $parent = Category::queryCategoriesCountChildAndArticle($request)
                    ->where("categories_final.id",$category->id_parent)->first();
                $Data->parent = CategoriesResource::make($parent);
            }
            $Data->categories = CategoriesResource::collection($brothers);
            return Application::getApp()->getHandleJson()
                ->DataHandle($Data);
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function SearchCategories(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_category" => ["nullable","numeric"],
                "name" => ["nullable","string"]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $categories = Category::queryCategoriesCountChildAndArticle($request,$request->id_category,$request->name,true);
            if($request->has("paginate")&&is_bool($request->paginate)){
                if ($request->paginate == true){
                    $final = $categories->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
                    return Application::getApp()->getHandleJson()
                        ->PaginateHandle("categories",CategoriesResource::collection($final));
                }else{
                    $final = $categories->get();
                    return Application::getApp()->getHandleJson()
                        ->DataHandle(CategoriesResource::collection($final),"categories");
                }
            }else{
                $final = $categories->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
                return Application::getApp()->getHandleJson()
                    ->PaginateHandle("categories",CategoriesResource::collection($final));
            }
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
