<?php

namespace App\Http\Controllers\User\Article;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FavoriteController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth:user","multi.auth:user"]);
    }

    public function ShowArticlesFavorite(Request $request): JsonResponse
    {
        try {
            $idsFav = auth()->user()->FavoritesArticles()->pluck("articles.id")->toArray();
            $articles = Article::queryArticleCategory($request,true)
                ->whereIn("c_articles.id",$idsFav)
                ->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
            return Application::getApp()->getHandleJson()
                ->PaginateHandle("articles",$articles);
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }


    public function AddOrRemoveFavorite(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_article" => ["required","numeric",Rule::exists("articles_publish","id_article")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $user = auth()->user();
            $favorite = Favorite::where("id_user",$user->id)->where("id_article",$request->id_article)->first();
            DB::beginTransaction();
            if(is_null($favorite)){
                Favorite::create([
                    "id_user" => $user->id,
                    "id_article" => $request->id_article
                ]);
                DB::commit();
                return Application::getApp()->getHandleJson()->DataHandle(Application::getApp()
                    ->getErrorMessages()["fav.suc"],"favorite");
            }else{
                $favorite->delete();
                DB::commit();
                return Application::getApp()->getHandleJson()->DataHandle(Application::getApp()
                    ->getErrorMessages()["fav.del"],"favorite");
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

}
