<?php

namespace Database\Seeders;

use App\Application\Application;
use App\Models\Article;
use App\Models\Article_Category;
use App\Models\ArticlePublish;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        #Article
//        for($i = 1 ; $i <= 40 ; $i++){
//            Article::create([
//                "id_writer" => random_int(1,10),
//                "lang" => "en"
//            ]);
//        }
       #Article_Category
        for ($i = 1 ; $i <= 10 ; $i++){
            Article_Category::create([
                "id_category" => random_int(1,21),
                "id_article" => random_int(1,40)
            ]);
        }
        #Article_Category
        for ($i = 1 ; $i <= 40 ; $i++){
            ArticlePublish::create([
                "id_article" => $i,
                "name" =>  Str::random(20),
                "description" => Str::random(100),
                "path_photo" => Application::getApp()->getUploadFiles()->DefaultPhotoPath()
            ]);
        }

    }
}
