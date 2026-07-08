<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LocaleSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            EditorUserSeeder::class,
            HomePageSeeder::class,
            BlogSeeder::class,
            SolutionsSeeder::class,
            ProductsSeeder::class,
            CaseStudiesSeeder::class,
            ResearchSeeder::class,
        ]);
    }
}
