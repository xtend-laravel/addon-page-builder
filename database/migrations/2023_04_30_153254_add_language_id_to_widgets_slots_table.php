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
        Schema::table('xtend_builder_widget_slots', function (Blueprint $table) {
            $table->foreignId('language_id')
                  ->after('id')
                  ->default(1)
                  ->constrained('lunar_languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xtend_builder_widget_slots', function (Blueprint $table) {
            $table->dropForeign('xtend_builder_widget_slots_language_id_foreign');
            $table->dropColumn('language_id');
        });
    }
};
