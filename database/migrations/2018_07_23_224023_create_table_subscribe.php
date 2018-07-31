<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubscribe extends Migration
{
    protected $tbl = 'subscribes';

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
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('name');
            $table->string('ip');
            $table->tinyInteger('type')->default(1);
            $table->time('time')->nullable();
            $table->string('code', 64)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->unique('email');
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
