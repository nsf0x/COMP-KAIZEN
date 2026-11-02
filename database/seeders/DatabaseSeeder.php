<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            CompanyInfoSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductAssetSeeder::class,
            PortfolioSeeder::class,
            ClientSeeder::class,
            TeamMemberSeeder::class,
            BlogPostSeeder::class,
            TestimonialSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
