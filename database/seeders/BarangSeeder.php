<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1, // Kategori: Kosmetik (KMS)
                'barang_kode' => 'KMS001',
                'barang_nama' => 'Lipstik Merah',
                'harga_beli' => 50000,
                'harga_jual' => 75000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1, // Kategori: Kosmetik (KMS)
                'barang_kode' => 'KMS002',
                'barang_nama' => 'Foundation',
                'harga_beli' => 60000,
                'harga_jual' => 90000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 2, // Kategori: Sepatu (SPT)
                'barang_kode' => 'SPT001',
                'barang_nama' => 'Sepatu Olahraga',
                'harga_beli' => 200000,
                'harga_jual' => 250000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2, // Kategori: Sepatu (SPT)
                'barang_kode' => 'SPT002',
                'barang_nama' => 'Sepatu Kulit',
                'harga_beli' => 300000,
                'harga_jual' => 400000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 3, // Kategori: Alat Tulis (ALC)
                'barang_kode' => 'ALC001',
                'barang_nama' => 'Pulpen Hitam',
                'harga_beli' => 5000,
                'harga_jual' => 10000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 3, // Kategori: Alat Tulis (ALC)
                'barang_kode' => 'ALC002',
                'barang_nama' => 'Buku Tulis',
                'harga_beli' => 15000,
                'harga_jual' => 25000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 4, // Kategori: Furniture (FUR)
                'barang_kode' => 'FUR001',
                'barang_nama' => 'Meja Kayu',
                'harga_beli' => 500000,
                'harga_jual' => 700000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 4, // Kategori: Furniture (FUR)
                'barang_kode' => 'FUR002',
                'barang_nama' => 'Kursi Sofa',
                'harga_beli' => 750000,
                'harga_jual' => 1000000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 5, // Kategori: Sport Equipment (SPE)
                'barang_kode' => 'SPE001',
                'barang_nama' => 'Bola Basket',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
                'created_at' => now(),
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 5, // Kategori: Sport Equipment (SPE)
                'barang_kode' => 'SPE002',
                'barang_nama' => 'Matras Yoga',
                'harga_beli' => 120000,
                'harga_jual' => 180000,
                'created_at' => now(),
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
