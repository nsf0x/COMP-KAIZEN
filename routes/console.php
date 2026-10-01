<?php

use App\Console\Commands\ImportKaizenProductsCommand;
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
