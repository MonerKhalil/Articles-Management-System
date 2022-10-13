<?php

namespace App\Http\Controllers\User\Article;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Content;
use App\Models\Visitor;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ArticlesController extends Controller
{
    public function SearchArticle(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_category" => ["nullable","array",Rule::exists("categories","id")],
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

    public function ShowArticle(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_article" => ["required","numeric",Rule::exists("articles_publish","id_article")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $article = Article::queryArticleCategory($request,false)->first();
            if(!Visitor::where("ip_client",$request->getClientIp())->exists()){
                DB::beginTransaction();
                $visitor = Visitor::create([
                    "ip_client" => $request->getClientIp()
                ]);
                $visitor->article()->syncWithoutDetaching([
                    "id_article" => $article->id
                ]);
                $article->update([
                    "views" => $article->CountViews()
                ]);
                DB::commit();
            }
            $article->contents = Content::select(DB::raw("contents_changes.id,contents_changes.type,contents_changes.value"))
                ->join("contents_saves","contents_saves.id","=","contents.id_content_save")
                ->join("contents_changes","contents_changes.id","=","contents_saves.id_content_change")
                ->where("contents_changes.id_article",$request->id_article)
                ->get();
            return Application::getApp()->getHandleJson()->DataHandle($article,"article");
        }catch (Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
