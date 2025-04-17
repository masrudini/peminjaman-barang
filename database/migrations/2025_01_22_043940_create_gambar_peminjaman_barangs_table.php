<?php

use App\Models\Peminjaman;
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
        Schema::create('gambar_peminjaman_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Peminjaman::class);
            $table->string('gambar_sebelum')->nullable();
            $table->string('gambar_sesudah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_peminjaman_barangs');
    }
};
