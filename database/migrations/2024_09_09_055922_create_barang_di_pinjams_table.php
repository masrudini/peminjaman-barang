<?php

use App\Models\Peminjaman;
use App\Models\KategoriBarang;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_di_pinjams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Peminjaman::class);
            $table->foreignIdFor(KategoriBarang::class);
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_di_pinjams');
    }
};
