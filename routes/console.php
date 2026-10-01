<?php

use App\Console\Commands\ImportKaizenProductsCommand;
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

Artisan::command('kaizen:bootstrap-production', function () {
    $seedExitCode = $this->call('db:seed', [
        '--class' => AdminUserSeeder::class,
        '--force' => true,
    ]);

    if ($seedExitCode !== 0) {
        return $seedExitCode;
    }

    return $this->call('kaizen:import-products', [
        '--file' => 'produk-kaizen-kreasi.json',
    ]);
})->purpose('Seed the admin user and import the Kaizen product catalog');
