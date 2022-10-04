<?php

namespace App\Http\Controllers;

use App\Application\Application;
use App\Mail\SendCodeMail;
use App\Mail\UserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPUnit\Exception;

class TestController extends Controller
{
    public function Test(Request $request)
    {
//        Http::get("http://localhost:4200/Authentication/SignIn")
        return redirect("http://localhost:4200/Authentication/SignIn");
//        dd($request);
//        $valide = Validator::make($request->all(),[
//
//        ]);
//        $valide->fails();$valide->errors()->toJson();
//        $file = $request->file("xxx");
//        return Application::getApp()->getUploadFiles()->Upload($file,"user");
////
//
////        DB::beginTransaction();
//        try {
////            $user = User::create([
////                "first_name" => "moner",
////                "last_name" => "khalil",
////                "slug_name" => Str::slug("moners12239_@ @@@khalil"),
////                "email" => "monerkhalsil@gmail.com",
////                "password" => "12345678",
////            ]);
////            $user = DB::table("users")->latest()->first();
////            DB::commit();
//            $user = User::latest()->first();
//            return Application::getApp()->getHandleJson()->DataHandle($user,"user");
//        }catch (Exception $exception){
////            DB::rollBack();
//        }
    }
}
