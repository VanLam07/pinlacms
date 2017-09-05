<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function(Blueprint $table){
            $table->increments('id');
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->tinyInteger('menu_type')->default(0);
            $table->integer('type_id')->nullable();
            $table->string('icon', 64)->nullable();
            $table->string('open_type', 15)->nullable();
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->foreign('group_id')->references('id')->on('taxs')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('set null');
        });
        
        Schema::create('menu_desc', function(Blueprint $table){
            $table->integer('menu_id')->unsigned();
            $table->string('lang_code', 3);
            $table->string('title');
            $table->string('slug');
            $table->string('link')->nullable();
            $table->primary(['menu_id', 'lang_code']);
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('lang_code')->references('code')->on('langs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_desc');
    }
}
