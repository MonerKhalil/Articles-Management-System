<?php

namespace Database\Seeders;

use App\Models\Favorite;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1 ; $i<10;$i++){
            Favorite::create([
                "id_user" => "1",
                "id_article" => $i
            ]);
        }
    }
}
