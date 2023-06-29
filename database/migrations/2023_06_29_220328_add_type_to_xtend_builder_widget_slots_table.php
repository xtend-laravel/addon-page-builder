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
            $table->enum('type', ['builder', 'cms'])->default('builder')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('xtend_builder_widget_slots', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
