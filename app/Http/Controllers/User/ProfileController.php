<?php

namespace App\Http\Controllers\User;

use App\Application\Application;
use App\Http\Controllers\Controller;
use App\Mail\SendCodeMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:user");
    }

    public function Show(): JsonResponse
    {
        try {
            $user = auth()->user();
            return Application::getApp()->getHandleJson()->DataHandle($user,"user");
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function Update(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "first_name" => ["nullable","string"],
                "last_name" => ["nullable","string"],
                "email" => ["nullable","string","email",Rule::unique("users","email")],
                "path_photo" => ["nullable",'mimes:jpeg,png,jpg'],
                "phone" => ["nullable","min:10","string"],
                "setting_lang" => ["nullable","string",Rule::in(["en","ar"])]
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()
                    ->ErrorsHandle("validate",$validate->errors());
            }
            DB::beginTransaction();
            $new_photo = null;
            $file = $request->file("path_photo");
            if (!is_null($file)&&$file->isValid()){
                $new_photo = Application::getApp()->getUploadFiles()->Upload($file,"user");
            }
            $user = auth()->user();
            $old_photo = $user->path_photo;
            $user->update([
                "first_name" => $request->first_name ?? $user->first_name,
                "last_name" => $request->last_name ?? $user->last_name,
                "slug_name" => $this->getSlugNew($request->first_name,$request->last_name,$user->first_name,$user->last_name),
                "phone" => $request->phone ?? $user->phone,
                "setting_lang" => $request->setting_lang ?? $user->setting_lang,
                "path_photo" => $new_photo ?? $old_photo
            ]);
            if (!is_null($new_photo)){
                Application::getApp()->getUploadFiles()->DeleteFile($old_photo);
            }
            DB::commit();
            return Application::getApp()->getHandleJson()->DataHandle($user,"user");
        }catch (\Exception $exception){
            Application::getApp()->getUploadFiles()->rollBackUpload();
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function SendCodeUpdateEmail(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "email" => ["required","string","email",Rule::unique("users","email")],
                //new email
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()
                    ->ErrorsHandle("validate",$validate->errors());
            }
            if (!Application::getApp()->isOnlineInternet()){
                return Application::getApp()->getHandleJson()
                    ->ErrorsHandle("internet",Application::getApp()->getErrorMessages()["online.err"]);
            }
            DB::beginTransaction();
            $user = auth()->user();
            $user->update([
                "email" => $request->email,
                "active" => false
            ]);
            $user->tokens()->delete();
            $code = $user->SendCodeToEmailActive();
            DB::commit();
            return $code;
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function UpdatePassword(Request $request): JsonResponse
    {
        try {
            $validate = Validator::make($request->all(),[
                "newpassword" => ["required","string","min:8"],
                "password" => ["required","string"],
            ],Application::getApp()->getErrorMessages());
            if($validate->fails()){
                return Application::getApp()->getHandleJson()
                    ->ErrorsHandle("validate",$validate->errors());
            }
            $user = auth()->user();
            if (password_verify($request->password,$user->password)){
                DB::beginTransaction();
                $user->tokens()->delete();
                $token = $user->createToken($user->slug_name,["*"])->plainTextToken;
                $user->update([
                    "password" => password_hash($request->newpassword,PASSWORD_DEFAULT),
                ]);
                DB::commit();
                $user->token = $token;
                return Application::getApp()->getHandleJson()->DataHandle($user,"user");
            } else{
                return Application::getApp()->getHandleJson()
                    ->ErrorsHandle("validate",["password" => [Application::getApp()->getErrorMessages()["password.err"]]]);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    public function Delete(): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            $photo = $user->path_photo;
            $user->tokens()->delete();
            $user->delete();
//            dd($photo);
            Application::getApp()->getUploadFiles()->DeleteFile($photo);
            DB::commit();
            return Application::getApp()->getHandleJson()
                ->DataHandle(Application::getApp()->getErrorMessages()["user.delete"],"message");
        }catch (\Exception $exception){
            DB::rollBack();
            return Application::getApp()->getHandleJson()
                ->ErrorsHandle("exception",$exception->getMessage());
        }
    }

    private function getSlugNew($Nfname,$Nlname,$Ofname,$Olname): string
    {
        if (!is_null($Nfname)&&!is_null($Nlname)){
            return Str::slug($Nfname.$Nlname);
        }
        elseif (!is_null($Nfname)&&is_null($Nlname)){
            return Str::slug($Nfname.$Olname);
        }elseif (is_null($Nfname)&&!is_null($Nlname)){
            return Str::slug($Ofname.$Nlname);
        }else{
            return Str::slug($Ofname.$Olname);
        }
    }
}
