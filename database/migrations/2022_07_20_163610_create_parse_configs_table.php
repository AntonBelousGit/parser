<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parse_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->boolean('enable')->default(1);
            $table->string('parser', 150);
            $table->string('connection', 20);
            $table->text('url');
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
        Schema::dropIfExists('parse_configs');
    }
};
