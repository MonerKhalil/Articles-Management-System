<?php

namespace App\Http\Requests;

use App\Application\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SingeUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "first_name" => ["required","string"],
            "last_name" => ["required","string"],
            "email" => ["required","string","email",Rule::unique("users","email")],
            "password" => ["required","string","min:8"],
            "password_c" => ["required","string","same:password"],
            "phone" => ["nullable","min:10","numeric"],
            "role" => ["nullable","string",Rule::in(["admin","user","writer"])],
        ];
    }
    public function messages()
    {
        if(Application::getApp()->getLang() == "en"){
            return [
                "first_name" => [
                    "required" => "The first_name input field is empty",
                    "string" => "The first_name input is not string",
                ],
                "last_name" => [
                    "required" => "The last_name input field is empty",
                    "string" => "The last_name input is not string",
                ],
                "email"=>[
                    "required" => "The email input field is empty",
                    "string" => "The email input is not string",
                    "email" => "The email input is not email",
                    "unique" => "The email input is exists",
                ],
                "password" => [
                    "required" => "The password input field is empty",
                    "string" => "The password input is not string",
                    "min" => "The password input is min 8 chars"
                ],
                "password_c" => [
                    "required" => "The password_c input field is empty",
                    "string" => "The password_c input is not string",
                    "same" => "The password_c input is not equal password"
                ],
                "phone" => [
                    "min" => "The phone input is min 10 numbers",
                    "numeric" => "The phone input is not numeric"
                ],
                "role" => [
                    "string" => "The role input is not string",
                    "in" => "The role input value not exists"
                ],
            ];
        }else{
            return [
                "first_name" => [
                    "required" => "حقل إدخال اسم_الاول مطلوب",
                    "string" => "حقل إدخال اسم_الاول يجب ان يكون قيمة نصية",
                ],
                "last_name" => [
                    "required" => "حقل إدخال اسم_الاخير مطلوب",
                    "string" => "حقل إدخال اسم_الاخير يجب ان يكون قيمة نصية",
                ],
                "email"=>[
                    "required" => "حقل إدخال الحساب مطلوب",
                    "string" => "حقل إدخال الحساب يجب ان يكون قيمة نصية",
                    "email" => "حقل إدخال الحساب لايمثل صيغة حساب",
                    "unique" => "حقل إدخال الحساب موجود مسبقا",
                ],
                "password" => [
                    "required" => "حقل إدخال كلمة المرور مطلوب",
                    "string" => "حقل إدخال كلمة المرور يجب ان يكون قيمة نصية",
                    "min" => "حقل إدخال كلمة المرور اقل من 8 احرف"
                ],
                "password_c" => [
                    "required" => "حقل إدخال كلمة المرور المؤكدة مطلوب",
                    "string" => "حقل إدخال كلمة المرور المؤكدة يجب ان يكون قيمة نصية",
                    "same" => "حقل إدخال كلمة المرور المؤكدة لا يساوي كلمة المرور الاصلية"
                ],
                "phone" => [
                    "min" => "حقل إدخال رقم الهاتف اقل من 10 ارقام",
                    "numeric" => "حقل إدخال رقم الهاتف يجب ان يكون قيمة عددية"
                ],
                "role" => [
                    "string" => "حقل إدخال رقم الهاتف يجب ان يكون قيمة نصية",
                    "in" => "حقل إدخال نوع المستخدم القيمة غير موجودة"
                ],
            ];
        }
    }
}
