<?php

namespace Database\Seeders;

use App\Application\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $role = ["writer","user","admin"];
        for ($i = 0 ; $i < 10 ;$i++){
            $first_name = Str::random("5");
            $last_name =  Str::random("5");
            User::create([
                "first_name" => $first_name,
                "last_name" => $last_name,
                "slug_name" => Str::slug($first_name.$last_name),
                "email" => $first_name.$last_name."@"."gmail.com",
                "password" => password_hash("12345678",PASSWORD_DEFAULT),
                "path_photo" => Application::getApp()->getUploadFiles()->DefaultPhotoPath(),
                "role" => $role[random_int(0,2)]
            ]);
        }
    }
}
