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
            $category = $this->queryGenerateCategory()
                ->where("categories_Child.id",$request->id_category)->first();
            return Application::getApp()->getHandleJson()
                ->DataHandle(CategoriesResource::make($category),"category");
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function ShowAllCategories(Request $request): JsonResponse
    {
        try {
            $order = Application::getApp()->OrderByData($request);
            $categories = $this->queryGenerateCategory()
                ->orderBy($order->type,$order->latest)
                ->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
            return Application::getApp()->getHandleJson()
                ->PaginateHandle("categories",CategoriesResource::collection($categories));
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function ShowChildCategory(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_category" => ["required","numeric",Rule::exists("categories","id")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $order = Application::getApp()->OrderByData($request);
            $categories = $this->queryGenerateCategory()
                ->orderBy($order->type,$order->latest)
                ->where("categories_Child.id_parent",$request->id_category)
                ->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
            return Application::getApp()->getHandleJson()
                ->PaginateHandle("categories",CategoriesResource::collection($categories));
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    private function queryGenerateCategory(){
        return Category::select(DB::raw("categories_Child.* ,COUNT(articles_categories.id_article) as articles"))
            ->from(DB::raw("(SELECT parent.* ,COUNT(child.id_parent) AS children
                        FROM categories as parent
                        LEFT JOIN categories as child ON child.id_parent = parent.id
                        GROUP BY parent.id) as categories_Child"))
            ->leftJoin("articles_categories","categories_Child.id","=","articles_categories.id_category")
            ->groupBy(["categories_Child.id"]);
    }
}
