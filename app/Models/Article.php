<?php

namespace App\Models;

use App\Application\Application;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\BelongsToMany;
use \Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Article extends Model
{
    use HasFactory;

    protected $table = "articles";

    protected $fillable = [
        "id_parent","id_writer","lang","views"
    ];

    protected $hidden = ["pivot"];

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
     *
     * @return HasMany
     */
    public function ChildrenArticle(): HasMany
    {
        return $this->hasMany(Article::class,"id_parent","id");
    }

    /**
     * @return BelongsTo
     */
    public function ParentArticle(): BelongsTo
    {
        return $this->belongsTo(Article::class,"id_parent","id");
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


    /**
     *
     *
     * @param Request $request
     * @param bool $order
     * @return mixed
     */
    public static function queryArticleCategory(Request $request,bool $order = false,int $id_article = null): mixed
    {
        $order = Application::getApp()->OrderByData($request);
        $query = Article::select(DB::raw("c_articles.*,COUNT(comments.id) as comments"))
            ->from(function ($query) use ($request,$id_article){
               $temp = $query->select(DB::raw("articles.id,articles.id_parent,articles.id_writer,articles_publish.name,articles_publish.description, articles_publish.path_photo,articles_publish.created_at,articles_publish.updated_at,articles.views,COUNT(child.id_parent) as children"))
                    ->from("articles")
                    ->join("articles_publish","articles_publish.id_article","=","articles.id")
                    ->leftJoin("articles as child","child.id_parent","=","articles.id");
                   if (!is_null($id_article)){
                       return $temp->where("articles.id",$id_article)->groupBy(["articles.id"]);
                   }
                   if($request->has("id_article")){
                       $temp->where("articles.id_parent",$request->id_article);
                   }
                   if($request->has("id_category")){
                       $temp->join("articles_categories","articles.id","=","articles_categories.id_article")
                            ->whereIn("articles_categories.id_category",$request->id_category);
                   }
                   if($request->has("name")){
                       $temp->where("articles_publish.name",$request->name);
                   }
                   return $temp->groupBy(["articles.id"]);
            },"c_articles")
            ->leftJoin("comments","comments.id_article","=","c_articles.id")
            ->groupBy(["c_articles.id"]);
        return $order ? $query->orderBy($order->type,$order->latest) : $query;
    }
}
/*
 *
SELECT c_articles.*,COUNT(comments.id) as comments
FROM (SELECT articles.id,articles.id_parent,articles.id_writer,articles.views,
      articles_publish.title,articles_publish.description,
      articles_publish.path_photo,articles_publish.created_at,articles_publish.updated_at
      ,COUNT(child.id_parent) as children
      FROM articles
      JOIN articles_categories ON articles.id = articles_categories.id_article
      JOIN articles_publish ON articles_publish.id_article = articles.id
	  LEFT JOIN articles as child ON child.id_parent = articles.id
      WHERE articles_categories.id_category IN (1)
      GROUP BY articles.id
     ) as c_articles
LEFT JOIN comments ON comments.id_article = c_articles.id
GROUP BY c_articles.id
*/
