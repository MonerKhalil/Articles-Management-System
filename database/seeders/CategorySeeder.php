<?php

namespace Database\Seeders;

use App\Application\Application;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{

    private function name_des_ar(): array
    {
        return [
            "التربة","المياه","السماء","الارض","الحرارة","الجفاف","حيوانات"
        ];
    }
    private function name_des_en(): array
    {
        return [
            "soil","water","sky","Earth","heat","Drought","animals"
        ];
    }
    private function des_ar(){
        return "نةينةسي يسنةيسنةسي نةيسنيسة نةسينةيس نةيسنةيسن نةيسنةيسنيس نيس ني نسينيسنسيةنيسةيسنةسين ين سني نيس نيس نيسنىنىنتىبيسيب نينةسي";
    }
    private function des_en(){
        return "slals,sl, sal,sal,sa salmomfimf .,domd dsi dsidisiefwfj cjidnndfoinfdoifnd dfsofoiddf ";
    }
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        for ($i = 0 ; $i < 20 ; $i++){
            $num = random_int(0,6);
            Category::create([
                "id_parent" => random_int(1,5),
                "name" => $this->name_des_ar()[$num],
                "name_en" => $this->name_des_en()[$num],
                "description" => $this->des_ar(),
                "description_en" => $this->des_en(),
                "path_photo" => Application::getApp()->getUploadFiles()->DefaultPhotoPath()
            ]);
        }
    }
}
