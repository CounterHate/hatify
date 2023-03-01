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
        Permission::create(['name' => 'remove_keywords']);
        Permission::create(['name' => 'start_stream']);
        Permission::create(['name' => 'download']);
        Permission::create(['name' => 'search_keywords']);
        Permission::create(['name' => 'see_authors']);
        Permission::create(['name' => 'admin']);
    }
}
