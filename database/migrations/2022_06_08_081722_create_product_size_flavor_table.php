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
        Schema::create('product_size_flavor', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 50)->references('id')->on('products')->onUpdate('cascade');
            $table->string('size_id', 50)->references('id')->on('sizes')->onUpdate('cascade');;
            $table->string('flavor_id', 50)->references('id')->on('flavors')->onUpdate('cascade');;
            $table->float('price')->nullable();
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
        Schema::dropIfExists('product_size_flavor');
    }
};
