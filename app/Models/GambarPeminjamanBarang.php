<?php

namespace App\Models;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GambarPeminjamanBarang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function peminjamanBarang()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
