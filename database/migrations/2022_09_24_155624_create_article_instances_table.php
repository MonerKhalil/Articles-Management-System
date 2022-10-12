<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_instances', function (Blueprint $table) {
            $table->id("id_article");
            $table->foreign("id_article")->references("id")->on("articles")->cascadeOnDelete();
            $table->string("name");
            $table->text("description");
            $table->text("path_photo")->nullable();
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
        Schema::dropIfExists('article_instances');
    }
}
