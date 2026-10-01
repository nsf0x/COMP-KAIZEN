<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class SyncKaizenProductPhotosCommand extends Command
{
    protected $signature = 'kaizen:sync-product-photos {--manifest=database/data/product-photos/manifest.json : Photo mapping manifest}';

    protected $description = 'Synchronize repository product photos to the public product upload disk';

    public function handle(): int
    {
        $manifestPath = base_path($this->option('manifest'));

        if (! is_file($manifestPath)) {
            $this->error("Manifest not found: {$manifestPath}");

            return self::FAILURE;
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        if (! is_array($manifest) || ! isset($manifest['photos']) || ! is_array($manifest['photos'])) {
            $this->error('Invalid manifest. Expected a top-level "photos" array.');

            return self::FAILURE;
        }

        $catalogPath = base_path('produk-kaizen-kreasi.json');
        $catalog = is_file($catalogPath)
            ? json_decode((string) file_get_contents($catalogPath), true)
            : null;

        if (! is_array($catalog) || ! isset($catalog['products']) || ! is_array($catalog['products'])) {
            $this->error('The product catalog JSON is missing or invalid.');

            return self::FAILURE;
        }

        $catalogProducts = [];

        foreach ($catalog['products'] as $item) {
            $name = trim((string) ($item['name'] ?? ''));

            if ($name !== '') {
                $catalogProducts[Str::slug($name)] = $item;
            }
        }

        $photoAssignments = [];
        $sourceDirectory = dirname($manifestPath);
        $hasFailures = false;
        $unmatchedProducts = 0;

        foreach ($manifest['photos'] as $photo) {
            $file = (string) ($photo['file'] ?? '');

            if (! preg_match('/\A[a-z0-9][a-z0-9.-]*\.(?:jpe?g|png|webp)\z/i', $file)) {
                $this->warn("Skipping unsafe photo filename: {$file}");
                $hasFailures = true;

                continue;
            }

            if (! is_file($sourceDirectory.DIRECTORY_SEPARATOR.$file)) {
                $this->warn("Photo source not found: {$file}");
                $hasFailures = true;

                continue;
            }

            foreach (($photo['products'] ?? []) as $mapping) {
                $mapping = is_string($mapping) ? ['slug' => $mapping] : $mapping;
                $slug = trim((string) ($mapping['slug'] ?? ''));

                if (! preg_match('/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', $slug)) {
                    $this->warn("Skipping invalid product slug in manifest: {$slug}");
                    $hasFailures = true;

                    continue;
                }

                $catalogItem = $catalogProducts[$slug] ?? [];
                $name = trim((string) ($mapping['name'] ?? $catalogItem['name'] ?? ''));
                $categoryName = trim((string) ($mapping['category'] ?? $catalogItem['category'] ?? ''));

                if ($this->containsCatering($slug, $name, $categoryName)) {
                    $this->warn("Skipping catering mapping: {$slug}");

                    continue;
                }

                $photoAssignments[$slug] ??= [
                    'name' => $name,
                    'category' => $categoryName,
                    'description' => (string) ($catalogItem['description'] ?? ''),
                    'order' => (int) ($catalogItem['sort_order'] ?? 0),
                    'photos' => [],
                ];

                $photoAssignments[$slug]['photos'][$file] = [
                    'file' => $file,
                    'source' => $sourceDirectory.DIRECTORY_SEPARATOR.$file,
                    'destination' => 'products/'.$file,
                ];

                if ($photoAssignments[$slug]['name'] === '' && $name !== '') {
                    $photoAssignments[$slug]['name'] = $name;
                }

                if ($photoAssignments[$slug]['category'] === '' && $categoryName !== '') {
                    $photoAssignments[$slug]['category'] = $categoryName;
                }
            }
        }

        $createdProducts = 0;
        $createdCategories = 0;
        $attachedPhotos = 0;
        $restoredPhotos = 0;
        $existingCatering = Product::query()
            ->where(function ($query): void {
                $query
                    ->where('name', 'like', '%katering%')
                    ->orWhere('slug', 'like', '%katering%')
                    ->orWhere('name', 'like', '%catering%')
                    ->orWhere('slug', 'like', '%catering%');
            })
            ->get(['name', 'slug']);

        if ($existingCatering->isNotEmpty()) {
            $this->warn('Existing catering products left unchanged: '.$existingCatering->pluck('name')->implode(', '));
        }

        foreach ($photoAssignments as $slug => $assignment) {
            $product = Product::query()->where('slug', $slug)->first();

            if (! $product) {
                if ($assignment['name'] === '' || $assignment['category'] === '' || $this->containsCatering($slug, $assignment['name'], $assignment['category'])) {
                    $unmatchedProducts++;

                    continue;
                }

                $categorySlug = Str::slug($assignment['category']);

                if ($categorySlug === '') {
                    $unmatchedProducts++;

                    continue;
                }

                [$product, $categoryWasCreated] = DB::transaction(function () use ($assignment, $categorySlug, $slug): array {
                    $category = Category::firstOrCreate(
                        ['slug' => $categorySlug],
                        [
                            'name' => $assignment['category'],
                            'type' => 'produk',
                            'icon' => 'tag',
                            'description' => null,
                            'order' => (Category::query()->max('order') ?? 0) + 1,
                            'is_active' => true,
                        ],
                    );

                    $product = Product::firstOrCreate(
                        ['slug' => $slug],
                        [
                            'category_id' => $category->id,
                            'name' => $assignment['name'],
                            'description' => $assignment['description'],
                            'specification' => null,
                            'price' => null,
                            'thumbnail' => null,
                            'is_featured' => false,
                            'status' => true,
                            'order' => $assignment['order'],
                            'meta_description' => Str::limit($assignment['description'], 160),
                        ],
                    );

                    return [$product, $category->wasRecentlyCreated];
                });

                if ($product->wasRecentlyCreated) {
                    $createdProducts++;
                }

                if ($categoryWasCreated) {
                    $createdCategories++;
                }
            }

            try {
                [$attached, $restored] = $this->syncProductPhotos($product, array_values($assignment['photos']));
                $attachedPhotos += $attached;
                $restoredPhotos += $restored;
            } catch (Throwable $exception) {
                $this->warn("Could not sync photos for {$slug}: {$exception->getMessage()}");
                $hasFailures = true;
            }
        }

        $this->info("Created products: {$createdProducts}; created categories: {$createdCategories}; photos attached: {$attachedPhotos}; missing files restored: {$restoredPhotos}; unmatched existing slugs skipped: {$unmatchedProducts}.");

        return $hasFailures ? self::FAILURE : self::SUCCESS;
    }

    private function syncProductPhotos(Product $product, array $photos): array
    {
        $disk = Storage::disk('public');
        $gallery = $product->images()->get();
        $references = [];

        if (filled($product->thumbnail)) {
            $references[] = ['type' => 'thumbnail', 'record' => null, 'path' => $product->thumbnail];
        }

        foreach ($gallery as $image) {
            $references[] = ['type' => 'gallery', 'record' => $image, 'path' => $image->image_path];
        }

        $validReferences = array_filter(
            $references,
            fn (array $reference): bool => $disk->exists($reference['path']),
        );

        if ($validReferences !== []) {
            $restored = 0;
            $photoIndex = 0;

            foreach ($references as $reference) {
                if ($disk->exists($reference['path'])) {
                    continue;
                }

                $photo = collect($photos)->firstWhere('destination', $reference['path'])
                    ?? $photos[min($photoIndex, count($photos) - 1)]
                    ?? null;

                if (! $photo) {
                    continue;
                }

                $this->copyPhotoIfMissing($photo);

                if ($reference['type'] === 'thumbnail' && $reference['path'] !== $photo['destination']) {
                    $product->thumbnail = $photo['destination'];
                    $product->save();
                } elseif ($reference['type'] === 'gallery' && $reference['path'] !== $photo['destination']) {
                    $reference['record']->image_path = $photo['destination'];
                    $reference['record']->save();
                }

                $restored++;
                $photoIndex++;
            }

            return [0, $restored];
        }

        $availablePhotos = [];

        foreach ($photos as $photo) {
            $this->copyPhotoIfMissing($photo);
            $availablePhotos[] = $photo['destination'];
        }

        if ($availablePhotos === []) {
            return [0, 0];
        }

        if ($product->thumbnail !== $availablePhotos[0]) {
            $product->thumbnail = $availablePhotos[0];
            $product->save();
        }

        foreach ($gallery as $index => $image) {
            if ($disk->exists($image->image_path)) {
                continue;
            }

            $replacementPath = $availablePhotos[min($index + 1, count($availablePhotos) - 1)];
            $image->image_path = $replacementPath;
            $image->save();
        }

        $existingGalleryPaths = $product->images()->pluck('image_path')->all();

        foreach (array_slice($availablePhotos, 1) as $index => $path) {
            if (in_array($path, $existingGalleryPaths, true)) {
                continue;
            }

            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'image_path' => $path],
                ['order' => $index + 1],
            );
        }

        return [1, 0];
    }

    private function copyPhotoIfMissing(array $photo): void
    {
        $disk = Storage::disk('public');

        if ($disk->exists($photo['destination'])) {
            return;
        }

        $contents = file_get_contents($photo['source']);

        if (! is_string($contents) || ! $disk->put($photo['destination'], $contents)) {
            throw new \RuntimeException("Unable to write {$photo['destination']} to the public disk.");
        }
    }

    private function containsCatering(string ...$values): bool
    {
        foreach ($values as $value) {
            if (Str::contains(Str::lower($value), ['katering', 'catering'])) {
                return true;
            }
        }

        return false;
    }
}
