<?php

namespace App\Http\Controllers\User\Article;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Article_Category;
use App\Models\Content;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ArticlesController extends Controller
{

    public function ArticleCategories(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_category" => ["nullable","array",Rule::exists("categories","id")],
                "id_article" => ["nullable","numeric",Rule::exists("articles_publish","id_article")],
                "name" => ["nullable","string"]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $articles = Article::queryArticleCategory($request,true)
                ->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
            return Application::getApp()->getHandleJson()
                ->PaginateHandle("articles",$articles);
        }catch (Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function ShowArticle(Request $request)
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_article" => ["required","numeric",Rule::exists("articles_publish","id_article")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $article = Article::queryArticleCategory($request,false,$request->id_article)->first();
            $article->contents = Content::select(DB::raw("contents_changes.id,contents_changes.type,contents_changes.value"))
                ->join("contents_saves","contents_saves.id","=","contents.id_content_save")
                ->join("contents_changes","contents_changes.id","=","contents_saves.id_content_change")
                ->where("contents_changes.id_article",$request->id_article)
                ->get();
            return $article;
        }catch (Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
