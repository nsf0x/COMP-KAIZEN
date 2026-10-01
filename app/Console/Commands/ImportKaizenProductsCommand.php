<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportKaizenProductsCommand extends Command
{
    protected $signature = 'kaizen:import-products {--file=produk-kaizen-kreasi.json : JSON file containing Kaizen products}';

    protected $description = 'Import Kaizen product catalog from the JSON file without duplicating data';

    public function handle(): int
    {
        $file = $this->option('file');
        $path = base_path($file);

        if (! is_file($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $contents = file_get_contents($path);
        $decoded = json_decode((string) $contents, true);

        if (! is_array($decoded) || ! isset($decoded['products']) || ! is_array($decoded['products'])) {
            $this->error('Invalid JSON structure. Expected a top-level "products" array.');
            return self::FAILURE;
        }

        $count = 0;

        foreach ($decoded['products'] as $item) {
            $categoryName = trim((string) ($item['category'] ?? 'Umum'));
            $productName = trim((string) ($item['name'] ?? ''));

            if ($categoryName === '' || $productName === '') {
                continue;
            }

            $categorySlug = Str::slug($categoryName) ?: 'umum';
            $category = Category::firstOrCreate(
                ['slug' => $categorySlug],
                [
                    'name' => $categoryName,
                    'type' => 'produk',
                    'icon' => 'tag',
                    'description' => null,
                    'order' => (Category::max('order') ?? 0) + 1,
                    'is_active' => true,
                ]
            );

            $slug = Str::slug($productName) ?: 'produk-' . ($count + 1);
            $unit = trim((string) ($item['unit'] ?? ''));
            $description = trim((string) ($item['description'] ?? ''));

            if ($unit !== '' && ! str_contains(strtolower($description), strtolower($unit))) {
                $description = trim($description . "\n\nSatuan: {$unit}");
            }

            Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $productName,
                    'slug' => $slug,
                    'description' => $description,
                    'specification' => null,
                    'price' => null,
                    'thumbnail' => null,
                    'is_featured' => false,
                    'status' => true,
                    'order' => (int) ($item['sort_order'] ?? ($count + 1)),
                    'meta_description' => Str::limit($description, 160),
                ]
            );

            $count++;
        }

        $this->info("Imported {$count} products from {$path}.");

        return self::SUCCESS;
    }
}
