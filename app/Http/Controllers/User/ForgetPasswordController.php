<?php

namespace App\Http\Controllers\User;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ForgetPasswordController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth:user"])->only("CreateNewPassword");
    }

    /**
     * Send code verify To Email because forget password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function InputEmail_GenerateCode(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "email" => ["required","string","email",Rule::exists("users","email")],
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $user = User::where("email",$request->email)->first();
            return $user->SendCodeToEmailActive();
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    /**
     * Generate new Token and Delete All Token user in project
     * and Check code verify
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function CheckCodeAndCreateNewToken(Request $request): JsonResponse{
        try {
            $validate = Validator::make($request->all(),[
                "email" => ["required","string","email",Rule::exists("users","email")],
                "code" => ["required","numeric"],
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()->ErrorsHandle("validate",$validate->errors());
            }
            $user = User::where("email",$request->email)->first();
            if (password_verify($request->code,$user->code)) {
                DB::beginTransaction();
                $user->tokens()->delete();
                $user->update([
                    "active" => true
                ]);
                $token = $user->createToken($user->slug_name, ["*"])->plainTextToken;
                DB::commit();
                return Application::getApp()->getHandleJson()->DataHandle($token,"token");
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

    /**
     * update password 
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function CreateNewPassword(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "newpassword" => ["required","string","min:8"],
                "code" => ["required","numeric"],
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()
                    ->ErrorsHandle("validate",$validate->errors());
            }
            $user = auth()->user();
            if(password_verify($request->code,$user->code)){
                DB::beginTransaction();
                $user->update([
                    "password" => password_hash($request->newpassword,PASSWORD_DEFAULT),
                ]);
                DB::commit();
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
