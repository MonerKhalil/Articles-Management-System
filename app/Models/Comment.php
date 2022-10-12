<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $table = "comments";

    protected $fillable = [
        "id_user","id_article","comment"
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class,"id_user","id");
    }
}
