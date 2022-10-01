<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("first_name");
            $table->string("last_name");
            $table->string('slug_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string("code")->nullable();
            $table->string("phone")->nullable();
            $table->text("path_photo")->nullable();
            $table->enum("role",["admin","writer","user"])->default("writer");
            $table->enum("setting_lang",["en","ar"])->default("ar");
            $table->boolean("active")->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
