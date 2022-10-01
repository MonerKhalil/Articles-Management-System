<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleAccept extends Model
{
    use HasFactory;

    protected $table = "articles_accepts";
    protected $primaryKey = "id_article";
    protected $fillable = [
        "id_article","type","note"
    ];

    public function Article(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Article::class,"id_article","id");
    }
}
