<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
use \Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    use HasFactory;

    protected $table = "articles";

    protected $fillable = [
        "id_parent","id_writer","lang","views"
    ];


    public function ArticleAccept(): HasOne
    {
        return $this->hasOne(ArticleAccept::class,"id_article","id");
    }

    public function ArticleInstance(): HasOne
    {
        return $this->hasOne(ArticleInstance::class,"id_article","id");
    }

    public function ArticlePublish(): HasOne
    {
        return $this->hasOne(ArticlePublish::class,"id_article","id");
    }


    /**
     * All Parents Articles
     * @return HasMany
     */
    public function ParentsArticle(): HasMany
    {
        return $this->hasMany(Article::class,"id_parent","id");
    }

    /**
     *  All  Childes Articles belonging to this Article Parent
     * @param $id_parent
     * @return mixed
     */
    public function ChildesArticle($id_parent): mixed
    {
        return Article::where("id_parent",$id_parent)->get();
    }

    /**
     * All Article comments
     *
     * @return HasMany
     */
    public function Comments(): HasMany
    {
        return $this->hasMany(Comment::class,"id_article","id");
    }

    /**
     * All categories belonging to this article
     *
     * @return BelongsToMany
     */
    public function Categories(): BelongsToMany
    {
       return $this->belongsToMany(Category::class,"articles_categories",
            "id_article","id_category",
            "id",
            "id"
        );
    }

    /**
     * article favorite users
     *
     * @return BelongsToMany
     */
    public function FavoritesUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class,
            "favorites",
            "id_article",
            "id_user",
            "id","id"
        );
    }

    /**
     * Count Views in Article
     * @return int
     */
    public function CountViews():int{
        return View::where("id_article",$this->id)->count();
    }

}
