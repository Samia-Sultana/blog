<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageModuleSeederForLive extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the roles
        // DB::table('modules')->insert([
        //     'name' => 'ai-seo-pages',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        // $adminRole = UserRole::where('name', 'Admin')->first();

        // $adminModuleIds = Module::where('name', 'ai-seo-pages')->pluck('id')->first();
        // $adminRole->modules()->attach($adminModuleIds);

        $module = Module::firstOrCreate(['name' => 'ai-seo-pages']);

        $adminRole = UserRole::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->modules()->syncWithoutDetaching($module->id);
        }

    }
}
