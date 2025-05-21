<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Wilayah extends Seeder
{
    public function run()
    {
        $hierarchyLevels = ['provinsi', 'kabupaten', 'kecamatan', 'desa'];

        foreach ($hierarchyLevels as $level) {
            $directoryPath = storage_path("app/wilayah/{$level}");
            $filesInDirectory = scandir($directoryPath);

            foreach ($filesInDirectory as $fileName) {
                if (in_array($fileName, ['.', '..'])) continue;

                $fileContent = file_get_contents("{$directoryPath}/{$fileName}");
                $records = json_decode($fileContent, true);

                foreach ($records as $record) {
                    DB::table('wilayah')->updateOrInsert(
                        ['id' => $record['id']],
                        [
                            'nama' => $record['name'],
                            'level' => $level,
                            'parent_id' => $record['parent_id'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}
