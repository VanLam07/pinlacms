<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDictSentences extends Migration
{
    protected $tbl = 'dict_sentences';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->tbl)) {
            return;
        }
        Schema::create($this->tbl, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('word_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->text('sentence');
            $table->timestamps();
            $table->foreign('word_id')->references('id')->on('dict_en_vn')
                    ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
