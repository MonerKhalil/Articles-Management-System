<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    protected $fillable = [
        "id_parent","name","name_en"
        ,"description","description_en","path_photo"
    ];

    /**
     * All Parents Categories
     * @return BelongsTo
     */
    public function ParentsCategories(): BelongsTo
    {
        return $this->belongsTo(Category::class,"id_parent","id");

    }
    /**
     * All Childes Categories
     * @return HasMany
     */
    public function ChildesCategories(): HasMany
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


}
