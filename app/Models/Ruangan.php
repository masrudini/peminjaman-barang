<?php

namespace App\Models;

use App\Models\PeminjamRuangan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function peminjaman()
    {
        return $this->hasMany(PeminjamRuangan::class);
    }
}
