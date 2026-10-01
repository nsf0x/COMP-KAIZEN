<?php

use App\Console\Commands\ImportKaizenProductsCommand;
use App\Console\Commands\SyncKaizenProductPhotosCommand;
use App\Models\Product;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('kaizen:import-products {--file=produk-kaizen-kreasi.json : JSON file containing Kaizen products}', function () {
    $this->call(ImportKaizenProductsCommand::class, [
        '--file' => $this->option('file'),
    ]);
})->purpose('Import Kaizen product catalog from the JSON file');

Artisan::command('kaizen:sync-product-photos {--manifest=database/data/product-photos/manifest.json : Photo mapping manifest}', function () {
    return $this->call(SyncKaizenProductPhotosCommand::class, [
        '--manifest' => $this->option('manifest'),
    ]);
})->purpose('Synchronize repository product photos to the public product upload disk');

Artisan::command('kaizen:bootstrap-production', function () {
    $seedExitCode = $this->call('db:seed', [
        '--class' => AdminUserSeeder::class,
        '--force' => true,
    ]);

    if ($seedExitCode !== 0) {
        return $seedExitCode;
    }

    if (Product::query()->doesntExist()) {
        return $this->call('kaizen:import-products', [
            '--file' => 'produk-kaizen-kreasi.json',
        ]);
    }

    return 0;
})->purpose('Seed the admin user and import the Kaizen catalog when the products table is empty');

Artisan::command('kaizen:production-startup {--manifest=database/data/product-photos/manifest.json : Photo mapping manifest}', function () {
    $bootstrapExitCode = $this->call('kaizen:bootstrap-production');

    try {
        $syncExitCode = $this->call('kaizen:sync-product-photos', [
            '--manifest' => $this->option('manifest'),
        ]);

        if ($syncExitCode !== 0) {
            $message = 'Product photo synchronization failed; startup will continue.';
            logger()->warning($message);
            $this->warn($message);
        }
    } catch (Throwable $exception) {
        logger()->warning('Product photo synchronization threw an exception; startup will continue.', [
            'exception' => $exception->getMessage(),
        ]);
        $this->warn('Product photo synchronization failed; startup will continue.');
    }

    return $bootstrapExitCode;
})->purpose('Bootstrap catalog data and synchronize product photos on every startup');
