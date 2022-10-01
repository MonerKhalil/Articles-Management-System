<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentSave extends Model
{
    use HasFactory;
    protected $table = "contents_saves";
    protected $fillable = [
        "id_content_change"
    ];
}
