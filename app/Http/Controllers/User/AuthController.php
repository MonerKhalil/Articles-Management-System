<?php

namespace App\Http\Controllers\User;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:user")->only("SingeOut");
    }

    /**
     * send code verify to email if not active after Register(SingeUp) if d`ont send code
     * ( no internet or any problem )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function SendCodeIfNotActive(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "email" => ["required","string","email",Rule::exists("users","email")]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $user = User::where("email",$request->email)->first();
            if($user->active===1){
                return Application::getApp()->getHandleJson()
                    ->DataHandle(Application::getApp()->getErrorMessages()["email.active"],"message");
            }
            return $user->SendCodeToEmailActive();
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    /**
     * Active Any Email After Send code verify
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function CheckCode_ActiveEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "email" => ["required","string","email",Rule::exists("users","email")],
                "code" => ["required","numeric"],
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $user = User::where("email",$request->email)->first();
            if($user->active===1){
                return Application::getApp()->getHandleJson()
                    ->DataHandle(Application::getApp()->getErrorMessages()["email.active"],"message");
            }
            return $user->ActiveEmail($request->code);
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    /**
     * Register Email And Send Code verify
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function SingeUp(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "first_name" => ["required","string"],
                "last_name" => ["required","string"],
                "email" => ["required","string","email",Rule::unique("users","email")],
                "password" => ["required","string","min:8"],
                "password_c" => ["required","string","same:password"],
                "phone" => ["nullable","min:10","numeric"],
                "role" => ["nullable","string",Rule::in(["admin","user","writer"])],
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            DB::beginTransaction();
            $name = $request->first_name.$request->last_name;
            $user = User::create([
                "first_name"=> $request->first_name,
                "last_name" => $request->last_name,
                "slug_name" => Str::slug($name),
                "email" => $request->email,
                "password" => password_hash($request->password,PASSWORD_DEFAULT),
                "path_photo" => Application::getApp()->getUploadFiles()->DefaultPhotoPath(),
                "role" => $request->role ?? "user",
                "phone" => $request->phone ?? null
            ]);
            $code = $user->SendCodeToEmailActive();
            DB::commit();
            return $code;
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    /**
     * login user in project if Email Active
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function SingeIn(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "email" => ["required","string","email",Rule::exists("users","email")],
                "password" => ["required","string"]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $user = User::where("email",$request->email)->first();
            if($user->active !== 0)
            {
                if (password_verify($request->password,$user->password)){
                    DB::beginTransaction();
                    $token = $user->createToken($user->slug_name,["*"])->plainTextToken;
                    $user->token = $token;
                    DB::commit();
                    return Application::getApp()->getHandleJson()->DataHandle($user,"user");
                } else{
                    return Application::getApp()->getHandleJson()
                    ->ErrorsHandle("validate",["password" => [Application::getApp()->getErrorMessages()["password.err"]]]);
                }
            } else{
                return Application::getApp()->getHandleJson()
                ->ErrorsHandle("email.active",Application::getApp()->getErrorMessages()["email.active.err"]);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function SingeOut(): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $user->currentAccessToken()->delete();
            DB::commit();
            return Application::getApp()->getHandleJson()->DataHandle(Application::getApp()->getErrorMessages()["logout"],"message");
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
