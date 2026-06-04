<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tourist_attractions', function (Blueprint $table) {
            // Used by Filament form
            $table->string('venue_name')->nullable()->after('name');
            $table->string('organizer')->nullable()->after('venue_name');
            $table->text('event_rules')->nullable()->after('description');

            // Used by TouristAttraction model fillable / public views
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('address')->nullable()->after('location');
            $table->text('short_description')->nullable()->after('description');
            $table->string('country')->nullable()->after('province');
            $table->string('postal_code')->nullable()->after('country');
            $table->string('phone')->nullable()->after('postal_code');
            $table->string('email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
            $table->text('terms_conditions')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('refund_policy')->nullable();
            $table->integer('max_capacity')->nullable();
            $table->integer('current_visitors')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->nullable()->default('active');
        });
    }

    public function down(): void
    {
        Schema::table('tourist_attractions', function (Blueprint $table) {
            $table->dropColumn([
                'venue_name', 'organizer', 'event_rules',
                'slug', 'address', 'short_description',
                'country', 'postal_code', 'phone', 'email', 'website',
                'terms_conditions', 'cancellation_policy', 'refund_policy',
                'max_capacity', 'current_visitors', 'is_featured', 'status',
            ]);
        });
    }
};
