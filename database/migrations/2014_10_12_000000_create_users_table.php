<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            // $table->string('email', 100)->unique();
            // $table->integer('role_id')->unsigned();
            // $table->foreign('role_id')->references('id')->on('roles');
            $table->foreignId('role_id')->default(1)->constrained('roles');
            // $table->integer('cart_id')->unsigned();
            // $table->foreign('cart_id')->references('id')->on('carts');
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
