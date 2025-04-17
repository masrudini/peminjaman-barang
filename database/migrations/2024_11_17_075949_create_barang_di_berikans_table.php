<?php

use App\Models\Barang;
use App\Models\Peminjaman;
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
        Schema::create('barang_di_berikans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Peminjaman::class);
            $table->foreignIdFor(Barang::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_di_berikans');
    }
};
