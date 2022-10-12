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
//        for($i = 0 ; $i < 10 ; $i++){
//            Article::create([
//                "id_parent" => random_int(1,10),
//                "id_writer" => random_int(2,11)
//            ]);
//        }
//      #Article_Category
//        for ($i = 0 ; $i < 10 ; $i++){
//            Article_Category::create([
//                "id_category" => random_int(1,25),
//                "id_article" => random_int(1,20)
//            ]);
//        }
      #Article_Category
//        for ($i = 1 ; $i <= 20 ; $i++){
//            ArticlePublish::create([
//                "id_article" => $i,
//                "title" =>  Str::random(10),
//                "description" => Str::random(100),
//                "path_photo" => Application::getApp()->getUploadFiles()->DefaultPhotoPath()
//            ]);
//        }

    }
}
