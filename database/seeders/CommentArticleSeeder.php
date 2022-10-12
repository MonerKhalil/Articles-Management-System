<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommentArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 2 ; $i < 12 ; $i++){
            Comment::create([
                "id_user" => $i,
                "id_article" => 1,
                "comment" => Str::random(50)
            ]);
        }
    }
}
