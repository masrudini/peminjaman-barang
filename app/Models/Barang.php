<?php

namespace App\Models;

use App\Models\KategoriBarang;
use App\Models\BarangDiBerikan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kategoriBarang()
    {
        return $this->belongsTo(kategoriBarang::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function barangDiBerikan()
    {
        return $this->hasMany(BarangDiBerikan::class);
    }
}
