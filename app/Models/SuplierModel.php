<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuplierModel extends Model
{
    use HasFactory;
    protected $table = 'm_suplier'; // Nama tabel di database
    protected $primaryKey = 'suplier_id'; // Primary key tabel

    protected $fillable = [
        'nama_suplier',
        'alamat_suplier',
        'telepon_suplier'
    ];
}