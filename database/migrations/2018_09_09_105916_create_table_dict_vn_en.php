<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDictVnEn extends Migration
{
    protected $tbl = 'dict_en_vn';

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
            $table->string('word');
            $table->string('origin');
            $table->string('pronun')->nullable();
            $table->string('mean')->nullable();
            $table->text('detail')->nullable();
            $table->text('detail_origin')->nullable();
            $table->index('origin');
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
