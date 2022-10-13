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
            "التربة","المياه","السماء","الارض","الحرارة","الجفاف","حيوانات","فواكه","خضراوات","سوائل",
            "سسسس","ششش","ةينسيةن","شسنةسش","ممسشة","ضهصه","سنةشس","نننن","ءءءئء","شششئ","ضضضصءئ",
        ];
    }
    private function name_des_en(): array
    {
        return [
            "soil","water","sky","Earth","heat","Drought","animals","fruits","vegetables","liquids",
            "assa","sakmei","qooqoq","nndnd","oasoo","qqwe1","mmomm","kaaqw","oaoa","ama","sam"
        ];
    }
    private function des_ar(){
        return "نةينةسي يسنةيسنةسي نةيسنيسة نةسينةيس نةيسنةيسن نةيسنةيسنيس نيس ني نسينيسنسيةنيسةيسنةسين ين سني نيس نيس نيسنىنىنتىبيسيب نينةسي";
    }
    private function des_en(){
        return "slals,sl, sal,sal,sa salmomfsakskamasimf .,domd dsi dsidisiefwfj cjidnndfoinfdoifnd dfsofoiddf ";
    }
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        for ($i = 10 ; $i <= 20 ; $i++){
//            $num = random_int(0,8);
            Category::create([
                "id_parent" => random_int(1,10),
                "name" => $this->name_des_ar()[$i],
                "name_en" => $this->name_des_en()[$i],
                "description" => $this->des_ar(),
                "description_en" => $this->des_en(),
                "path_photo" => Application::getApp()->getUploadFiles()->DefaultPhotoPath()
            ]);
        }
    }
}
