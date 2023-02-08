<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'anotate']);
        Permission::create(['name' => 'remove keywords']);
        Permission::create(['name' => 'start stream']);
        Permission::create(['name' => 'download']);
        Permission::create(['name' => 'search keywords']);
        Permission::create(['name' => 'see authors']);
        Permission::create(['name' => 'admin']);
    }
}
