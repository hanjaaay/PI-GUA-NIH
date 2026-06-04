<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tourist_attraction_id')->constrained()->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->string('booking_code')->unique();
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->dateTime('visit_date');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
