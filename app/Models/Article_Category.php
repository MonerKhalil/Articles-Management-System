<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_Category extends Model
{
    use HasFactory;
    protected $table = "articles_categories";
    protected $fillable = [
        "id_category" , "id_article"
    ];
}
