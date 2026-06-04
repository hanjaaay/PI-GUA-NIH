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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'pending_payment',
                'paid',
                'confirmed',
                'completed',
                'cancelled',
                'failed',
                'refunded',
            ])->default('pending')->after('visit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
            ])->default('pending')->after('visit_date');
        });
    }
};
