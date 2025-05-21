<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Desa extends Seeder
{
    public function run(): void
    {
        $folderPath = storage_path('app/wilayah/kelurahan_desa');

        foreach (File::files($folderPath) as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME); // kab-11
            $parts = explode('-', $filename);
            $provinsiId = $parts[1] ?? null;

            if (!$provinsiId) continue;

            $content = File::get($file);
            $json = json_decode($content, true);

            if (!is_array($json)) {
                dump("Gagal decode file: " . $file);
                dd($content); // debug isi mentah
                continue;
            }

            foreach ($json as $localId => $name) {
                $id = str_pad($provinsiId, 2, '0', STR_PAD_LEFT) . str_pad($localId, 2, '0', STR_PAD_LEFT);

                DB::table('wilayah')->updateOrInsert(
                    ['id' => $id],
                    [
                        'name' => $name,
                        'level' => 2,
                        'parent_id' => $provinsiId,
                    ]
                );
            }
        }
    }
}
