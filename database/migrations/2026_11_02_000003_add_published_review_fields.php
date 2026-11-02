<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table): void {
            $table->boolean('is_published')->default(false);
        });

        Schema::table('testimonials', function (Blueprint $table): void {
            $table->string('event_type')->nullable();
            $table->string('rented_product')->nullable();
            $table->string('location')->nullable();
            $table->date('event_date')->nullable();
            $table->boolean('is_published')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropColumn(['event_type', 'rented_product', 'location', 'event_date', 'is_published']);
        });

        Schema::table('clients', function (Blueprint $table): void {
            $table->dropColumn('is_published');
        });
    }
};
