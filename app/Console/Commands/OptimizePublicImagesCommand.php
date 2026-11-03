<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OptimizePublicImagesCommand extends Command
{
    protected $signature = 'kaizen:optimize-images {--public-assets : Also convert static files in public/images}';

    protected $description = 'Create WebP sidecars for public image assets without replacing originals';

    public function handle(): int
    {
        if (!function_exists('imagewebp') || !function_exists('imagecreatefromstring')) {
            $this->error('PHP GD with WebP support is required to optimize images.');

            return self::FAILURE;
        }

        $converted = 0;
        $disk = Storage::disk('public');

        foreach ($disk->allFiles() as $path) {
            if (!$this->isConvertible($path)) {
                continue;
            }

            $destination = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
            $sourcePath = $disk->path($path);
            $destinationPath = $disk->path($destination);

            if (File::exists($destinationPath)) {
                continue;
            }

            if (!$this->convert($sourcePath, $destinationPath)) {
                return self::FAILURE;
            }

            $converted++;
        }

        if ($this->option('public-assets')) {
            foreach (File::allFiles(public_path('images')) as $file) {
                $path = $file->getPathname();
                if (!$this->isConvertible($path)) {
                    continue;
                }

                $destinationPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
                if (File::exists($destinationPath)) {
                    continue;
                }

                if (!$this->convert($path, $destinationPath)) {
                    return self::FAILURE;
                }

                $converted++;
            }
        }

        $this->info("Created {$converted} WebP image(s); original files were preserved.");

        return self::SUCCESS;
    }

    private function isConvertible(string $path): bool
    {
        return in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'], true);
    }

    private function convert(string $sourcePath, string $destinationPath): bool
    {
        $contents = file_get_contents($sourcePath);
        if ($contents === false) {
            $this->error("Unable to read image: {$sourcePath}");

            return false;
        }

        $image = imagecreatefromstring($contents);
        if ($image === false) {
            $this->error("Unable to decode image: {$sourcePath}");

            return false;
        }

        File::ensureDirectoryExists(dirname($destinationPath));
        $converted = imagewebp($image, $destinationPath, 82);
        imagedestroy($image);

        if (!$converted) {
            $this->error("Unable to write WebP image: {$destinationPath}");

            return false;
        }

        return true;
    }
}
