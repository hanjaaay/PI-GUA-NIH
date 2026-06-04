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
            // Tambahkan kolom 'gallery' dengan tipe data JSON.
            // Kita set nullable() karena tidak semua atraksi mungkin punya galeri.
            $table->json('gallery')->nullable()->after('featured_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourist_attractions', function (Blueprint $table) {
            // Drop kolom 'gallery' jika migrasi di-rollback
            $table->dropColumn('gallery');
        });
    }
};
