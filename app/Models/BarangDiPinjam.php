<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriBarang;


class BarangDiPinjam extends Model
{
    use HasFactory;

    protected $guarded=['id'];

    public function kategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class);
    }
}
