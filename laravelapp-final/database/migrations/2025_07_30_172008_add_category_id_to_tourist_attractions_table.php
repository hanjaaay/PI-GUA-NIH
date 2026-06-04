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
            // Hapus kolom 'category' jika sebelumnya digunakan sebagai string
            // $table->dropColumn('category'); // Hati-hati jika ada data di kolom 'category'
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->after('location');
            // Ganti 'after('location')' dengan kolom yang relevan di skema Anda.
            // Gunakan ->nullable() jika atraksi bisa tanpa kategori.
            // Gunakan onDelete('cascade') jika ingin atraksi ikut terhapus saat kategori dihapus.
            // onDelete('set null') lebih aman jika kategori dihapus.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_attractions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            // Jika Anda menghapus kolom 'category' di up, Anda bisa menambahkannya kembali di down jika diperlukan
            // $table->string('category')->nullable()->after('location');
        });
    }
};
