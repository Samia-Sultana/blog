<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            'home',
            'users',
            'roles',
            'blog-category',
            'blog',
            'og-images',
            'subscribe',
            'contacts',
            'ai-page-contacts',
            'ai-seo-pages',
            'company-deck',
            'seo-service',
            'case-study',
            'job-portal',
            'job-application',
            'status-label',
            'backup',
            'export',
        ];

        foreach ($modules as $moduleName) {
            Module::updateOrCreate(
                ['name' => $moduleName], // Conditions to find the record
                ['name' => $moduleName]  // Values to update or insert
            );
        }
    }
}
