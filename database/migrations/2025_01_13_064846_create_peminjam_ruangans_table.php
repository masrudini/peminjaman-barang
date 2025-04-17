<?php

use App\Models\Ruangan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjam_ruangans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Ruangan::class)->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('no_hp');
            $table->string('kegiatan');
            $table->string('tanggal_pinjam');
            $table->string('jam_mulai');
            $table->string('jam_selesai');
            $table->enum('status', ['Pending', 'Diterima', 'Selesai', 'Ditolak'])->default('Pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjam_ruangans');
    }
};
