<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'kategori_kode' => 'KMS',
                'kategori_nama' => 'Kosmetik',
                'created_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'kategori_kode' => 'SPT',
                'kategori_nama' => 'Sepatu',
                'created_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'kategori_kode' => 'ALC',
                'kategori_nama' => 'Alat Tulis',
                'created_at' => now(),
            ],
            [
                'kategori_id' => 4,
                'kategori_kode' => 'FUR',
                'kategori_nama' => 'Furniture',
                'created_at' => now(),
            ],
            [
                'kategori_id' => 5,
                'kategori_kode' => 'SPE',
                'kategori_nama' => 'Sport Equipment',
                'created_at' => now(),
            ],
        ];
        DB::table('m_kategori')->insert($data);
    }
}
