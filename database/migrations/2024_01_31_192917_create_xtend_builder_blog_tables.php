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
        Schema::create('xtend_builder_blog_categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug')->nullable()->unique();
            $table->json('description')->nullable();
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
        });

        Schema::create('xtend_builder_blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lunar_staff_id')->nullable()->constrained('lunar_staff')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('xtend_builder_blog_categories')->nullOnDelete();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('excerpt')->nullable();
            $table->json('content')->nullable();
            $table->string('banner')->nullable();
            $table->string('status')->default('draft');
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
        Schema::dropIfExists('xtend_builder_blog_posts');
        Schema::dropIfExists('xtend_builder_blog_categories');
    }
};
