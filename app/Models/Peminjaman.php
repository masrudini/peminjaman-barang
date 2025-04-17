<?php

namespace App\Models;

use App\Models\BarangDiPinjam;
use App\Models\KategoriBarang;
use App\Models\GambarPeminjamanBarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'peminjamans';

    public function barangDiPinjam()
    {
        return $this->hasMany(BarangDiPinjam::class);
    }

    public function barangDiBerikan()
    {
        return $this->hasMany(BarangDiBerikan::class);
    }

    public function kategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class);
    }

    public function gambarPeminjamanBarang()
    {
        return $this->hasMany(GambarPeminjamanBarang::class);
    }
}
