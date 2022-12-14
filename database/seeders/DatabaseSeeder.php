<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
//        $this->call(UserSeeder::class);
//        $this->call(CategorySeeder::class);
//        $this->call(ArticlesSeeder::class);
//        $this->call(ArticleContentSeeder::class);
//        $this->call(CommentArticleSeeder::class);
        $this->call(FavoriteSeeder::class);
    }
}
