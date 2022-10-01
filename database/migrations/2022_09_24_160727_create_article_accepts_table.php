<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleAcceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_accepts', function (Blueprint $table) {
            $table->id("id_article");
            $table->foreign("id_article")->references("id")->on("articles")->cascadeOnDelete();
            $table->enum("type",["accept","unAccept","wait","edit"])->default("edit");
            $table->text("note")->default("");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_accepts');
    }
}
