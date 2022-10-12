<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\ContentChange;
use App\Models\ContentSave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #change
//        for ($i = 0;$i<20;$i++){
//            ContentChange::create([
//               "id_article" => 1,
//               "value" => Str::random(50)
//            ]);
//        }
        #save
        for ($i = 1;$i<=20;$i++){
            ContentSave::create([
                "id_content_change" => $i
            ]);
        }
        #publish
        for ($i = 1;$i<=20;$i++){
            Content::create([
                "id_content_save" => $i
            ]);
        }
    }
}
