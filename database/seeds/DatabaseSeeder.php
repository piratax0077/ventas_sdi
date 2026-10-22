<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $catalogs = json_decode(file_get_contents(database_path('catalogs.json')), true, flags: JSON_THROW_ON_ERROR);
        foreach ($catalogs as $table => $rows) {
            foreach (array_chunk($rows, 100) as $chunk) {
                \Illuminate\Support\Facades\DB::table($table)->insertOrIgnore($chunk);
            }
        }
        \Spatie\Permission\Models\Role::findOrCreate('Vendedor', 'web');
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
