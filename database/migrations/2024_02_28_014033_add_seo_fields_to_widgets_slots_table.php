<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('xtend_builder_widget_slots', function (Blueprint $table) {
            $table->after('params', function (Blueprint $table) {
                $table->json('seo_title')->nullable();
                $table->json('seo_description')->nullable();
                $table->json('seo_keywords')->nullable();
                $table->string('seo_image')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('xtend_builder_widget_slots', function (Blueprint $table) {
            $table->dropColumn('seo_title');
            $table->dropColumn('seo_description');
            $table->dropColumn('seo_keywords');
            $table->dropColumn('seo_image');
        });
    }
};
