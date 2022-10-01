<?php

namespace App\Models;

use App\Application\Application;
use App\Mail\SendCodeMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name','last_name','slug_name','path_photo','code','phone',
        'email',
        'password',
        'role','setting_lang',"active"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "slug_name","code",
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];


    /**
     * All tasks for the writer
     *
     * @return HasMany
     */
    public function Tasks(): HasMany
    {
        return $this->hasMany(Task::class,"id_writer","id");
    }

    /**
     * All tasks of a writer for a specific category
     *
     * @param int $id_Category
     * @return Collection
     */
    public function TasksCategory(int $id_Category): Collection
    {
        return $this->Tasks()->where("id_category",$id_Category)->get();
    }


    /**
     * All user comments
     *
     * @return HasMany
     */
    public function Comments(): HasMany
    {
        return $this->hasMany(Comment::class,"id_user","id");
    }

    /**
     * All private comments of a user within a specific article
     *
     * @param int $id_article
     * @return HasMany
     */
    public function CommentsArticle(int $id_article): HasMany
    {
        return $this->Comments()->where("id_article",$id_article);
    }

    /**
     * All Replies
     *
     * @return HasMany
     */
    public function Replies(): HasMany
    {
        return $this->hasMany(Reply::class,"id_user","id");
    }

    /**
     * All private replies of a user within a specific comment
     *
     * @param int $id_comment
     * @return HasMany
     */
    public function RepliesComment(int $id_comment): HasMany
    {
        return $this->Replies()->where("id_comment",$id_comment);
    }


    /**
     * User favorite articles
     *
     * @return BelongsToMany
     */
    public function FavoritesArticles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class,"favorites",
            "id_user",
            "id_article",
            "id","id"
        );
    }

    public function GenerateCode(): \Illuminate\Http\JsonResponse|int
    {
        try {
            $code = random_int(100000,999999);
            $temp = $code;
            DB::beginTransaction();
            $this->update([
                "code" => password_hash($code,PASSWORD_DEFAULT)
            ]);
            DB::commit();
            return $temp;
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function SendCodeToEmailActive(): JsonResponse
    {
        $code = $this->GenerateCode();
        if($code instanceof JsonResponse){
            return $code;
        }
        if(!Application::getApp()->isOnlineInternet()){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("internet",Application::getApp()->getErrorMessages()["online.err"]);
        }
        $data = str_split($code);
        Mail::to($this->email)->send(new SendCodeMail($this,$data));
        return Application::getApp()->getHandleJson()->DataHandle(Application::getApp()
            ->getErrorMessages()["send.code.suc"],"message");
    }

    public function ActiveEmail($code): JsonResponse
    {
        try {
            if (password_verify($code,$this->code)){
                DB::beginTransaction();
                $this->tokens()->delete();
                $token = $this->createToken($this->slug_name,["*"])->plainTextToken;
                $this->update([
                    "active" => true
                ]);
                DB::commit();
                $user = $this;
                $user->token = $token;
                return Application::getApp()->getHandleJson()->DataHandle($user,"user");
            }else{
                return Application::getApp()->getHandleJson()
                    ->ErrorsHandle("code",Application::getApp()->getErrorMessages()["code.err"]);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
