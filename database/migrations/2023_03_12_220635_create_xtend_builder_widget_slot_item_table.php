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
        Schema::create('xtend_builder_widget_slot_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('widget_id');
            $table->foreignId('widget_slot_id');
            $table->integer('slot_col_start')->nullable();
            $table->integer('slot_row_start')->nullable();
            $table->integer('slot_cols')->nullable();
            $table->integer('slot_rows')->nullable();
            $table->integer('position')->default(1)->index();
            $table->json('data')->nullable();
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
        Schema::dropIfExists('xtend_builder_widget_slot_item');
    }
};
