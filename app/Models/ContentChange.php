<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentChange extends Model
{
    use HasFactory;

    protected $table = "contents_changes";

    protected $fillable = [
        "id_article","type","value"
    ];
}
