<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLangsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('langs', function(Blueprint $table){
           $table->string('name');
           $table->string('code', 3)->unique();
           $table->string('icon');
           $table->string('folder');
           $table->string('unit', 5);
           $table->float('ratio_currency')->default(1.00);
           $table->integer('order')->default(0);
           $table->tinyInteger('status')->default(1);
           $table->tinyInteger('default')->default(0);
           $table->primary('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('langs');
    }
}
