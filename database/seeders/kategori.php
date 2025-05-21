<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class kategori extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            [
                'name' => 'Elektronik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'fashion',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'motor',
                'created_at' => now(),
                'updated_at' => now()
            ]
            ];
            DB::table('kategori')->insert($kategori);
    }
}
