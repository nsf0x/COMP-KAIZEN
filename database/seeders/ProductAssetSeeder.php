<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductAssetSeeder extends Seeder
{
    public function run(): void
    {
        $disk = Storage::disk('public');

        $thumbnails = [
            'paket-sound-system-medium' => 'products/sound-system.jpeg',
            'paket-sound-system-large' => 'products/qline.jpeg',
            'tenda-roder-span-10m-15m-20m' => 'products/grand-stand-tent.jpeg',
            'tenda-sarnafil-3x3m-dan-5x5m' => 'products/tenda-sarnafil-3x3m-dan-5x5m.jpeg',
            'tenda-konvensional' => 'products/tenda-konvensional.jpeg',
            'ringlock-system' => 'products/ringlock-system-vertical-dan-horizontal.jpeg',
            'led-screen-p26-indoor-dan-outdoor' => 'products/led-screen-p26-indoor-outdoor-all-curve-straight.jpeg',
            'led-screen-p39-indoor-dan-outdoor' => 'products/led-screen-p39-indoor-dan-outdoor.jpeg',
            'led-cube-p25-48x48cm' => 'products/led-cube-p25-48x48cm.jpeg',
            'genset-kapasitas-150-kva-100-kva-80-kva-60-kva-dan-5000-watt' => 'products/genset-5000-watt-sampai-150-kva.jpeg',
            'sound-system-event' => 'products/sound-system.jpeg',
            'lighting-system' => 'products/led-par-light.jpeg',
            'stage-dan-rigging' => 'products/panggung-lighting-event.jpeg',
            'furniture-dan-seating-event' => 'products/sofa-2-seater.jpeg',
            'produksi-booth-backdrop-dan-branding' => 'products/produksi-gate.jpeg',
            'toilet-portable-dan-standing-ac' => 'products/toilet-portable.jpeg',
        ];

        foreach (Product::where('status', true)->get() as $product) {
            $path = $thumbnails[$product->slug] ?? null;
            $product->update(['thumbnail' => $path && $disk->exists($path) ? $path : null]);
        }

        ProductImage::whereIn('image_path', [
            'products/furniture-dan-seating-event/bean-bag.jpeg',
            'products/furniture-dan-seating-event/sofa-event.jpeg',
            'products/toilet-portable-dan-standing-ac/toilet-portable.jpeg',
        ])->delete();

        $galleries = [
            'furniture-dan-seating-event' => [
                'products/sofa-2-seater.jpeg',
                'products/bean-bag.jpeg',
                'products/kursi-caffe-kayu-kotak.jpeg',
                'products/kursi-curvy-black-dan-meja.jpeg',
                'products/set-kursi-dining-table.jpeg',
                'products/set-kursi-parasol.jpeg',
                'products/round-table.jpeg',
            ],
            'toilet-portable-dan-standing-ac' => [
                'products/toilet-portable.jpeg',
                'products/standing-ac.jpeg',
            ],
            'lighting-system' => [
                'products/led-par-light.jpeg',
                'products/moving-head-be-eye.jpeg',
                'products/moving-head-xmlite-artemis.jpeg',
                'products/misty-fan.jpeg',
            ],
            'produksi-booth-backdrop-dan-branding' => [
                'products/produksi-gate.jpeg',
                'products/partisi-r8-dan-rowing.jpeg',
            ],
            'tenda-roder-span-10m-15m-20m' => [
                'products/grand-stand-tent.jpeg',
                'products/grand-stand-tent-riging.jpeg',
            ],
        ];

        foreach ($galleries as $productSlug => $paths) {
            $product = Product::where('slug', $productSlug)->first();

            if (!$product) {
                continue;
            }

            foreach ($paths as $index => $path) {
                if (!$disk->exists($path)) {
                    continue;
                }

                ProductImage::updateOrCreate(
                    ['product_id' => $product->id, 'image_path' => $path],
                    ['order' => $index + 1],
                );
            }
        }

    }
}
