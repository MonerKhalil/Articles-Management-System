<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    protected $fillable = [
        "id_parent","name","slug","name_en","slug_en"
        ,"description","description_en","path_photo"
    ];

    protected $hidden = [
        "slug","slug_en"
    ];


    /**
     * All Parents Categories
     * @return HasMany
     */
    public function ParentsCategories(): HasMany
    {
        return $this->hasMany(Category::class,"id_parent","id");
    }

    /**
     *  All  Childes Categories belonging to this Category Parent
     * @param $id_parent
     * @return mixed
     */
    public function ChildesCategory($id_parent): mixed
    {
        return Category::where("id_parent",$id_parent)->get();
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


}
