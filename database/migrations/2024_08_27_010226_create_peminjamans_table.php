<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nomor_hp');
            $table->string('unit_kerja');
            $table->string('kegiatan');
            $table->string('waktu_peminjaman');
            $table->string('waktu_pengembalian');
            $table->string('keterangan')->nullable();
            $table->enum('status', ['Pending', 'Diterima', 'Ditolak', 'Selesai'])->default('Pending');
            $table->longText('ttd_peminjam'); // For storing base64 signature
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
