<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('payment_code')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('IDR');
            $table->string('payment_method'); // bank_transfer, e_wallet, credit_card
            $table->string('payment_channel')->nullable(); // bca, mandiri, bni, gopay, ovo, etc
            $table->string('payment_status')->default('pending'); // pending, paid, failed, expired
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
