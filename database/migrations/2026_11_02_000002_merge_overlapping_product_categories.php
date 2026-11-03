<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $this->mergeCategory(
                'tenda-flooring',
                'Tenda & Flooring',
                ['tenda-flooring', 'tenda', 'flooring-karpet'],
                'tent',
                1,
            );
            $this->mergeCategory(
                'led-screen-processor',
                'LED Screen & Processor',
                ['led-screen-processor', 'led-screen'],
                'display',
                2,
            );
        });
    }

    public function down(): void
    {
    }

    private function mergeCategory(string $canonicalSlug, string $name, array $aliases, string $icon, int $order): void
    {
        $categories = DB::table('categories')
            ->where('type', 'produk')
            ->whereIn('slug', $aliases)
            ->orderByRaw('CASE WHEN slug = ? THEN 0 ELSE 1 END', [$canonicalSlug])
            ->orderBy('id')
            ->get();

        $canonical = $categories->first();
        $canonicalId = $canonical?->id;

        if (!$canonicalId) {
            $canonicalId = DB::table('categories')->insertGetId([
                'name' => $name,
                'slug' => $canonicalSlug,
                'type' => 'produk',
                'icon' => $icon,
                'order' => $order,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $sourceIds = $categories
            ->pluck('id')
            ->reject(fn ($id): bool => (int) $id === (int) $canonicalId)
            ->values()
            ->all();

        if ($sourceIds !== []) {
            DB::table('products')->whereIn('category_id', $sourceIds)->update([
                'category_id' => $canonicalId,
                'updated_at' => now(),
            ]);
            DB::table('categories')->whereIn('id', $sourceIds)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
        }

        DB::table('categories')->where('id', $canonicalId)->update([
            'name' => $name,
            'slug' => $canonicalSlug,
            'type' => 'produk',
            'icon' => $icon,
            'order' => $order,
            'is_active' => true,
            'updated_at' => now(),
        ]);
    }
};
