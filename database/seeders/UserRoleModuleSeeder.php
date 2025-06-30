<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the roles
        $adminRole = UserRole::where('name', 'Admin')->first();
        $editorRole = UserRole::where('name', 'Editor')->first();

        // Get all module IDs for Admin role
        $adminModuleIds = Module::pluck('id')->toArray();
        $adminRole->modules()->attach($adminModuleIds);

        // Get specific module ID for Editor role
        $editorModuleId = Module::where('name', 'Home')->pluck('id')->first();
        $editorRole->modules()->attach($editorModuleId);
    }
}
