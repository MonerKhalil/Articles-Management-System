<?php

namespace App\Http\Controllers\User\Article;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ReplyCommentResource;
use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommentsArticleController extends Controller
{
    public function __construct()
    {
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
