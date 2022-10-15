<?php

namespace App\Models;

use App\Application\Application;
use App\Application\DB\OrderByData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    protected $fillable = [
        "id_parent","name","name_en"
        ,"description","description_en","path_photo"
    ];

    /**
     *
     * @return BelongsTo
     */
    public function ParentCategories(): BelongsTo
    {
        return $this->belongsTo(Category::class,"id_parent","id");

    }
    /**
     * All Childes Categories
     * @return HasMany
     */
    public function ChildrenCategories(): HasMany
    {
        return $this->hasMany(Category::class,"id_parent","id");
    }


    /**
     * All tasks for the category
     *
     * @return HasMany
     */
    public function Tasks(): HasMany
    {
        return $this->hasMany(Task::class,"id_category","id");
    }

    /**
     * All tasks of a writer for a specific category
     *
     * @param int $id_Writer
     * @return HasMany
     */
    public function TasksCategory(int $id_Writer): HasMany
    {
        return $this->Tasks()->where("id_writer",$id_Writer);
    }

    /**
     * All articles belonging to this category
     *
     * @return BelongsToMany
     */
    public function Articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class,"articles_categories",
            "id_category",
            "id_article",
            "id",
            "id"
        );
    }


    /**
     * Search (name) in all categories or child category
     * or
     * get query all categories
     *
     * @param Request $request
     * @param int|null $id_category
     * @param string|null $name
     * @param bool $order
     * @return mixed
     */
    public static function queryCategoriesCountChildAndArticle(Request $request, int $id_category=null, string $name =null , bool $order = false): mixed
    {
        $orderBy = Application::getApp()->OrderByData($request);
        $query = Category::select(DB::raw("categories_final.* ,COUNT(articles_categories.id_article) as articles"))
            ->from(function ($q) use ($id_category,$name){
                $temp = $q->select(DB::raw("parent.* ,COUNT(child.id_parent) AS children"))
                    ->from("categories","parent");
                if(!is_null($id_category)){
                    $temp->where("parent.id_parent",$id_category!=0 ? $id_category : null);
                }
                if(!is_null($name)){
                    $temp->where(function ($query) use ($name){
                        $query->where("parent.name","like",'%'.$name.'%')
                            ->orwhere("parent.name_en","like",'%'.$name.'%');
                    });
                }
                return $temp->leftJoin("categories as child","child.id_parent","=","parent.id")
                            ->groupBy(["parent.id"]);

            },"categories_final")
            ->leftJoin("articles_categories","categories_final.id","=","articles_categories.id_category")
            ->groupBy(["categories_final.id"]);
        if($order){
            if ($orderBy->type==="name"){
                $orderBy->type = Application::getApp()->getLang()==="ar" ? "name" : "name_en";
            }
            return $query->orderBy($orderBy->type,$orderBy->latest);
        }
        return $query;
    }
}
