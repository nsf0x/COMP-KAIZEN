<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Update this seeder with real company data when available.
 * Run: php artisan db:seed --class=CompanyInfoSeeder
 */
class CompanyInfoSeeder extends Seeder {
    public function run(): void {
        // Company info is stored in config/company.php
        // Edit that file or set values via .env
        $this->command->info('Company info is configured via config/company.php and .env file.');
        $this->command->info('Edit APP_NAME, COMPANY_WHATSAPP, COMPANY_ADDRESS in your .env file.');
    }
}
