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
        Schema::table('tourist_attractions', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('operating_hours')->nullable();
            $table->string('opening_hours')->nullable()->change();
            $table->string('closing_hours')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_attractions', function (Blueprint $table) {
            $table->dropColumn(['type', 'city', 'province', 'operating_hours']);
            $table->string('opening_hours')->nullable(false)->change();
            $table->string('closing_hours')->nullable(false)->change();
        });
    }
};
