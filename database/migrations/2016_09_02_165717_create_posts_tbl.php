<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('posts')) {
            return;
        }
        Schema::create('posts', function(Blueprint $table){
            $table->increments('id');
            $table->integer('thumb_id')->unsigned()->nullable();
            $table->string('thumb_urls')->nullable();
            $table->integer('author_id')->unsigned()->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('comment_status')->default(1);
            $table->integer('comment_count')->default(0);
            $table->string('post_type', 30)->default('post');
            $table->integer('views')->default(0);
            $table->string('template')->nullable();
            $table->boolean('is_auth')->default(0);
            $table->boolean('is_feature')->default(0);
            $table->index(['id', 'post_type']);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
        });
        
        Schema::create('post_desc', function(Blueprint $table){
            $table->integer('post_id')->unsigned();
            $table->string('lang_code', 3);
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->text('custom')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_desc')->nullable();
            $table->primary(['post_id', 'lang_code']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('lang_code')->references('code')->on('langs')->onDelete('cascade');
        });
        
        Schema::create('post_tax', function(Blueprint $table){
            $table->integer('post_id')->unsigned();
            $table->integer('tax_id')->unsigned();
            $table->primary(['post_id', 'tax_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_desc');
        Schema::dropIfExists('post_tax');
    }
}
