<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    // Menampilkan semua kategori barang
    public function index()
    {
        if (request()->has('search')) {
            $kategori_barang = KategoriBarang::where('nama_kategori', 'like', '%' . request('search') . '%')->get();
        } else {
            $kategori_barang = KategoriBarang::all();
        }
        return view('kategori_barang.index', compact('kategori_barang'));
    }

    // Menyimpan kategori barang baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        KategoriBarang::create($request->all());

        return redirect()->route('kategori_barang.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Memperbarui data kategori barang di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori_barang = KategoriBarang::findOrFail($id);
        $kategori_barang->update($request->all());

        return redirect()->route('kategori_barang.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori barang dari database
    public function destroy($id)
    {
        $kategori_barang = KategoriBarang::findOrFail($id);
        $kategori_barang->delete();

        return redirect()->route('kategori_barang.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
