<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePostMailNotify extends Migration
{
    protected $tbl = 'post_notify';

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
            $table->unsignedInteger('post_id');
            $table->string('email');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->time('from_hour')->nullable();
            $table->time('to_hour')->nullalbe();
            $table->integer('number_alert')->default(0);
            $table->text('array_time')->nullable();
            $table->timestamps();
            $table->foreign('post_id')->references('id')->on('posts')
                    ->onUpdate('cascade')
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
        //
    }
}
