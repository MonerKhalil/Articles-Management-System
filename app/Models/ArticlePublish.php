<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticlePublish extends Model
{
    use HasFactory;

    protected $table = "articles_publish";

    protected $primaryKey = "id_article";

    protected $fillable = [
        "id_article","title","description","path_photo"
    ];

    public function Article(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Article::class,"id_article","id");
    }
}
