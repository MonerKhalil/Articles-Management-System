<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Visitor extends Model
{
    use HasFactory;
    protected $table = "visitors";
    protected $fillable = [
        "ip_client"
    ];

    public function article(): BelongsToMany
    {
        return $this->belongsToMany(Article::class,
            "views",
            "id_visitor",
            "id_article",
            "id","id"
        );
    }
}
