<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('good')->unsigned();
            $table->foreign('good')->references('id')->on('goods');
            $table->integer('buyer')->unsigned();
            $table->foreign('buyer')->references('id')->on('users');
            $table->float('price', 7, 2)->default(0.0);
            $table->integer('discount')->default(0);
            $table->boolean('is_returned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
