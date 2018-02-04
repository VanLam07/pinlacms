<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTbl extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (Schema::hasTable('roles')) {
            return;
        }
        
        Schema::create('roles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('label')->nullable();
            $table->string('name', 32)->unique();
            $table->tinyInteger('default')->default(0);
            $table->text('list_caps')->nullable();
        });
        
        Schema::create('caps', function(Blueprint $table) {
            $table->string('name', 32);
            $table->string('label')->nullable();
            $table->primary('name');
        });

        Schema::create('role_cap', function(Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->string('cap_name', 32);
            $table->tinyInteger('level')->default(1);
            $table->primary(['role_id', 'cap_name']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('cap_name')->references('name')->on('caps')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('caps');
        Schema::dropIfExists('role_cap');
    }

}
