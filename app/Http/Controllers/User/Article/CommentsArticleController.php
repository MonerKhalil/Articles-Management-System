<?php

namespace App\Http\Controllers\User\Article;

use App\Application\Application;
use App\Events\CommentEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ReplyCommentResource;
use App\Models\Comment;
use App\Models\Reply;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\WebSocketException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommentsArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth:user"])->only("AddComment");
    }

    public function AddComment(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_article" => ["required","numeric",Rule::exists("articles_publish","id_article")],
                "comment" => ["required","string"],
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $user = auth()->user();
            $comment = Comment::create([
                "id_user" => $user->id,
                "id_article" => $request->id_article,
                "comment" => $request->comment
            ]);
            $comment->user_role = $user->role;
            if (Application::getApp()->isOnlineInternet()){
                Artisan::call("php artisan websockets:serve");
                broadcast(new CommentEvent($request->id_article,$user,$comment));
            }
            return Application::getApp()->getHandleJson()->DataHandle($comment,"comment");
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function ShowCommentsArticle(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_article" => ["required","numeric",Rule::exists("articles_publish","id_article")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $comments = Comment::select([
                "comments.*","users.first_name","users.last_name","users.path_photo","users.role"
            ])->where("comments.id_article",$request->id_article)
                  ->join("users","users.id","=","comments.id_user")
                  ->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
            return Application::getApp()->getHandleJson()
                ->PaginateHandle("comments",CommentResource::collection($comments));
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function ShowRepliesComment(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "id_comment" => ["required","numeric",Rule::exists("comments","id")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $replies = Reply::select([
                "replies.*","users.first_name","users.last_name","users.path_photo","users.role"
            ])->where("replies.id_comment",$request->id_comment)
                ->join("users","users.id","=","replies.id_user")
                ->paginate(Application::getApp()->getHandleJson()->NumberOfValues($request));
            return Application::getApp()->getHandleJson()
                ->PaginateHandle("comments",ReplyCommentResource::collection($replies));
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
