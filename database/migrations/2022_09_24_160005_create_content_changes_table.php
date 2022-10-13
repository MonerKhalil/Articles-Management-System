<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_article")->constrained("articles","id")->cascadeOnDelete();
            $table->enum("type",["title","text","image","video"])->default("text");
            $table->text("value");
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
        Schema::dropIfExists('content_changes');
    }
}
