<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_suplier')->insert([
            [
                'nama_suplier' => 'PT Sepatu Nusantara',
                'alamat_suplier' => 'Jl. Merdeka No. 10, Probolinggo',
                'telepon_suplier' => '081234567890',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_suplier' => 'CV Face Beauty',
                'alamat_suplier' => 'Jl. Raya Bogor KM 20, Surabaya',
                'telepon_suplier' => '085678901234',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_suplier' => 'UD Tani Makmur',
                'alamat_suplier' => 'Desa Sejahtera, Malang',
                'telepon_suplier' => '082345678901',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}