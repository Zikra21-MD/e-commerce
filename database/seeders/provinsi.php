<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Provinsi extends Seeder
{
    public function run(): void
    {
        $filename = storage_path('app/wilayah/provinsi/provinsi.json');
        $records = json_decode(file_get_contents($filename), true);

        foreach ($records as $id => $name) {
            DB::table('wilayah')->updateOrInsert(
                ['id' => $id],
                ['name' => $name, 'level' => 1, 'parent_id' => null]
            );
        }
    }
}

