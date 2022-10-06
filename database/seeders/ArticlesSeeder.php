<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Article_Category;
use Illuminate\Database\Seeder;

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
//        for($i = 0 ; $i < 10 ; $i++){
//            Article::create([
//                "id_writer" => random_int(2,11)
//            ]);
//        }

        for ($i = 0 ; $i < 10 ; $i++){
            Article_Category::create([
                "id_category" => random_int(1,25),
                "id_article" => random_int(1,10)
            ]);
        }
    }
}
