<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

    protected $fillable = ['username', 'nama', 'password', 'level_id', 'created_at', 'updated_at'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed' // Laravel akan otomatis mengenkripsi password
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    //mendapatkan nama role
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    //cek apakah user memiliki role tertentu
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }
}