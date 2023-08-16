<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use XtendLunar\Addons\PageBuilder\Enums\WidgetType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xtend_builder_widgets', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Advertisement', 'Content', 'Collection', 'Form'])->default('Advertisement');
            $table->string('name');
            $table->string('component');
            $table->integer('col_start')->nullable();
            $table->integer('row_start')->nullable();
            $table->integer('cols')->nullable();
            $table->integer('rows')->nullable();
            $table->json('data')->nullable();
            $table->json('params')->nullable();
            $table->boolean('enabled')->default(true);
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
        Schema::dropIfExists('xtend_builder_widgets');
    }
};
