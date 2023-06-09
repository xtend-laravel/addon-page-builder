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
        Schema::create('xtend_builder_widget_slots', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['builder', 'cms'])->default('cms');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('identifier')->unique();
            $table->boolean('enabled')->default(true);
            $table->json('params')->nullable();
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
        Schema::dropIfExists('xtend_builder_widget_slots');
    }
};
