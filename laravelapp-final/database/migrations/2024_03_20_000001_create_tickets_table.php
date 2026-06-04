<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tourist_attraction_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // regular, vip, package, children, etc.
            $table->decimal('price', 10, 2);
            $table->integer('available_quantity');
            $table->integer('quota');
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('qr_code')->unique()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
