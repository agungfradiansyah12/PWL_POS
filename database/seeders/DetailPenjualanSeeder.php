<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['detail_id' => 1, 'penjualan_id' => 1, 'barang_id' => 1, 'harga' => 25000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 2, 'penjualan_id' => 2, 'barang_id' => 3, 'harga' => 30000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 3, 'penjualan_id' => 3, 'barang_id' => 5, 'harga' => 40000, 'jumlah' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 4, 'penjualan_id' => 4, 'barang_id' => 2, 'harga' => 20000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 5, 'penjualan_id' => 5, 'barang_id' => 7, 'harga' => 50000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 6, 'penjualan_id' => 6, 'barang_id' => 4, 'harga' => 35000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 7, 'penjualan_id' => 7, 'barang_id' => 8, 'harga' => 45000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 8, 'penjualan_id' => 8, 'barang_id' => 6, 'harga' => 32000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 9, 'penjualan_id' => 9, 'barang_id' => 10, 'harga' => 60000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 10, 'penjualan_id' => 10, 'barang_id' => 9, 'harga' => 55000, 'jumlah' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 11, 'penjualan_id' => 1, 'barang_id' => 2, 'harga' => 22000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 12, 'penjualan_id' => 2, 'barang_id' => 4, 'harga' => 37000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 13, 'penjualan_id' => 3, 'barang_id' => 6, 'harga' => 29000, 'jumlah' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 14, 'penjualan_id' => 4, 'barang_id' => 8, 'harga' => 41000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 15, 'penjualan_id' => 5, 'barang_id' => 10, 'harga' => 53000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 16, 'penjualan_id' => 6, 'barang_id' => 1, 'harga' => 26000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 17, 'penjualan_id' => 7, 'barang_id' => 3, 'harga' => 34000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 18, 'penjualan_id' => 8, 'barang_id' => 5, 'harga' => 42000, 'jumlah' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 19, 'penjualan_id' => 9, 'barang_id' => 7, 'harga' => 48000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 20, 'penjualan_id' => 10, 'barang_id' => 9, 'harga' => 55000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 21, 'penjualan_id' => 1, 'barang_id' => 4, 'harga' => 38000, 'jumlah' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 22, 'penjualan_id' => 2, 'barang_id' => 6, 'harga' => 31000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 23, 'penjualan_id' => 3, 'barang_id' => 8, 'harga' => 47000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 24, 'penjualan_id' => 4, 'barang_id' => 10, 'harga' => 52000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 25, 'penjualan_id' => 5, 'barang_id' => 2, 'harga' => 24000, 'jumlah' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 26, 'penjualan_id' => 6, 'barang_id' => 4, 'harga' => 39000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 27, 'penjualan_id' => 7, 'barang_id' => 6, 'harga' => 30000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 28, 'penjualan_id' => 8, 'barang_id' => 8, 'harga' => 45000, 'jumlah' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 29, 'penjualan_id' => 9, 'barang_id' => 10, 'harga' => 57000, 'jumlah' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['detail_id' => 30, 'penjualan_id' => 10, 'barang_id' => 9, 'harga' => 56000, 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}
