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
        Schema::create('xtend_builder_cms_page', function (Blueprint $table) {
            $table->id();
            $table->foreignId('widget_slot_id');
            $table->string('image_upload')->nullable();
            $table->json('heading')->nullable();
            $table->json('content')->nullable();
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
        Schema::dropIfExists('xtend_builder_cms_page');
    }
};
