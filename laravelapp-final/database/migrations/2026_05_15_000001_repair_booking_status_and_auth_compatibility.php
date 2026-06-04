<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('status', 50)->default('pending')->change();
                $table->string('payment_status', 50)->default('pending')->change();
            });
        }

        if (Schema::hasTable('tourist_attractions')) {
            Schema::table('tourist_attractions', function (Blueprint $table) {
                if (! Schema::hasColumn('tourist_attractions', 'start_date')) {
                    $table->dateTime('start_date')->nullable()->after('price');
                }

                if (! Schema::hasColumn('tourist_attractions', 'end_date')) {
                    $table->dateTime('end_date')->nullable()->after('start_date');
                }
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
