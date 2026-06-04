<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_tickets', function (Blueprint $table) {

            $table->id();

            $table->foreignId('booking_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('ticket_code')->unique();

            $table->longText('qr_code')->nullable();

            $table->boolean('is_used')->default(false);

            $table->timestamp('used_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_tickets');
    }
};
