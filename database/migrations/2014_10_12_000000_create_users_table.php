<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('type', 32)->default('normal');
            $table->string('name');
            $table->string('slug');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('gender')->nullable();
            $table->timestamp('birth')->nullable();
            $table->unsignedInteger('image_id')->nullable();
            $table->string('image_url')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('resetPasswdToken')->nullable();
            $table->bigInteger('resetPasswdExpires')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('user_role', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
            $table->primary(['user_id', 'role_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('user_role');
    }
}
