<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConsolidateKaizenCatalogCommand extends Command
{
    protected $signature = 'kaizen:consolidate-catalog';

    protected $description = 'Apply the current Kaizen catalog names and remove discontinued products';

    public function handle(): int
    {
        $this->deleteProducts([
            'tenda-sarnafil-flooring-karpet-layak-3x3m',
            'tenda-sarnafil-flooring-karpet-layak-5x5m',
            'led-screen-p26-curve-outdoor',
            'operator-led-biasa',
        ]);

        $this->mergeProducts(
            ['flooring-karpet-layak'],
            'Flooring',
            "Tinggi maksimal 10cm.\n\nSatuan: per m²",
        );

        $this->mergeProducts(
            ['smart-tv-75-inch', 'smart-tv-65-inch', 'smart-tv-55-inch'],
            'Smart TV 75 Inch, 65 Inch, dan 55 Inch',
            "Ukuran 75 inch, 65 inch, dan 55 inch. Sudah termasuk kabel power 15m dan kabel HDMI 20m.\n\nSatuan: per unit",
        );

        $this->mergeProducts(
            [
                'partisi-r8-ruang-2-x-2m',
                'partisi-r8-ruang-2-x-3m',
                'partisi-r8-ruang-3-x-3m',
                'partisi-r8-ruang-3-x-4m',
            ],
            'Partisi R8',
            "Partisi R8 untuk kebutuhan pembagian area event.\n\nSatuan: per ruang",
        );

        return self::SUCCESS;
    }

    /**
     * @param  list<string>  $slugs
     */
    private function deleteProducts(array $slugs): void
    {
        Product::query()->whereIn('slug', $slugs)->delete();
    }

    /**
     * @param  list<string>  $sourceSlugs
     */
    private function mergeProducts(array $sourceSlugs, string $name, string $description): void
    {
        $slug = Str::slug($name);
        $products = Product::query()
            ->whereIn('slug', [...$sourceSlugs, $slug])
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        if ($products->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($description, $name, $products, $slug): void {
            $target = $products->firstWhere('slug', $slug) ?? $products->first();
            $targetImages = ProductImage::query()
                ->where('product_id', $target->id)
                ->pluck('image_path')
                ->all();

            foreach ($products->where('id', '!=', $target->id) as $source) {
                foreach (ProductImage::query()->where('product_id', $source->id)->orderBy('order')->get() as $image) {
                    if (in_array($image->image_path, $targetImages, true)) {
                        $image->delete();

                        continue;
                    }

                    $image->product_id = $target->id;
                    $image->save();
                    $targetImages[] = $image->image_path;
                }
            }

            $target->fill([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'thumbnail' => $target->thumbnail ?: $products->first(fn (Product $product): bool => filled($product->thumbnail))?->thumbnail,
                'is_featured' => $products->contains(fn (Product $product): bool => $product->is_featured),
                'status' => $products->contains(fn (Product $product): bool => $product->status),
                'order' => $products->min('order'),
                'meta_description' => Str::limit(strip_tags($description), 160),
            ]);
            $target->save();

            $products->where('id', '!=', $target->id)->each->delete();
        });
    }
}
