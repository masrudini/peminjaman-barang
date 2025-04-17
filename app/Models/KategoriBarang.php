<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\BarangDiPinjam;
use App\Models\Peminjaman;



class KategoriBarang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

    public function barangDiPinjam()
    {
        return $this->hasMany(BarangDiPinjam::class);
    }

    public function peminjaman(){
        return $this->hasMany(Peminjaman::class);
    }
}
