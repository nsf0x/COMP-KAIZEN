<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('area_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->longText('unique_content')->nullable();
            $table->text('delivery_time')->nullable();
            $table->text('venue_details')->nullable();
            $table->text('portfolio_details')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        $now = now();
        DB::table('area_pages')->insert([
            ['slug' => 'jakarta', 'title' => 'Sewa Perlengkapan Event Jakarta', 'is_published' => false, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'bekasi', 'title' => 'Sewa Perlengkapan Event Bekasi', 'is_published' => false, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'depok', 'title' => 'Sewa Perlengkapan Event Depok', 'is_published' => false, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'bogor', 'title' => 'Sewa Perlengkapan Event Bogor', 'is_published' => false, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'tangerang', 'title' => 'Sewa Perlengkapan Event Tangerang', 'is_published' => false, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'indonesia', 'title' => 'Sewa Perlengkapan Event Indonesia', 'is_published' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('area_pages');
    }
};
