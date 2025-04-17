<?php

namespace App\Models;

use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeminjamRuangan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
