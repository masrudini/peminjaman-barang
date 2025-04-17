<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Barang::query();
        $kategori = KategoriBarang::all();
        $barang = Barang::latest()->first(); // Mengambil barang terakhir
        if ($barang) {
            $barang_id = $barang->id + 1;
        } else {
            $barang_id = 1;
        }

        if ($search) {
            $query->where('nama_barang', 'LIKE', "%{$search}%")
                ->orWhere('kode_barang', 'LIKE', "%{$search}%")
                ->orWhere('kondisi', 'LIKE', "%{$search}%")
                ->orWhere('status', 'LIKE', "%{$search}%")
                ->orWhere('tgl_masuk', 'LIKE', "%{$search}%");
        }

        $barang = $query->get();
        return view('barang.list', compact('barang', 'kategori', 'barang_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'tgl_masuk' => 'required|date',
            'kode_barang' => 'required|string|max:50',
            'kondisi' => 'required|in:bagus,rusak',
            'kategori_barang_id' => 'required|exists:kategori_barangs,id',  // Ensure category exists in the database
        ]);

        // Store the image in the 'public/gambar-barang' directory
        $imagePath = $request->file('gambar')->store('gambar-barang', 'public');

        // Create new barang record
        $barang = Barang::create([
            'nama_barang' => $request->nama_barang,
            'image' => $imagePath,
            'tgl_masuk' => $request->tgl_masuk,
            'kode_barang' => $request->kode_barang,
            'kondisi' => $request->kondisi,
            'kategori_barang_id' => $request->kategori_barang_id,
            'qr_code' => '-',  // Placeholder for QR code
        ]);

        // Generate QR code for the newly created barang
        $barcodeLink = route('barang.show', $barang->id); // Use the show route to generate the QR code link
        $qrCodeApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($barcodeLink);

        // Save the QR code image locally
        $qrCodeImagePath = 'qrcode/' . $barang->id . '.png';
        $qrCodeImageContent = file_get_contents($qrCodeApiUrl); // Fetch QR code image from the API
        if ($qrCodeImageContent) {
            Storage::disk('public')->put($qrCodeImagePath, $qrCodeImageContent); // Store QR code image in public storage
        }

        // Update the barang record with the QR code path
        $barang->update([
            'qr_code' => $qrCodeImagePath
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    // Menampilkan detail barang berdasarkan ID
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    // Menampilkan form untuk mengedit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategori = KategoriBarang::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    // Memperbarui data barang yang sudah ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'tgl_masuk' => 'required|date',
            'kode_barang' => 'required|string|max:50',
            'kondisi' => 'required|in:bagus,rusak',
            'kategori_barang_id' => 'nullable|exists:kategori_barangs,id',
        ]);

        $barang = Barang::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Remove the old image
            if ($barang->image && Storage::disk('public')->exists($barang->image)) {
                Storage::disk('public')->delete($barang->image);
            }

            // Store the new image
            $data['image'] = $request->file('gambar')->store('gambar-barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    // Menghapus barang berdasarkan ID
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // Remove the image if it exists
        if ($barang->image && Storage::disk('public')->exists($barang->image)) {
            Storage::disk('public')->delete($barang->image);
        }

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    // Cetak barang terpilih
    public function printSelected(Request $request)
    {
        $selected = json_decode($request->input('selected_ids'), true);

        if (empty($selected) || !is_array($selected)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih untuk dicetak.');
        }

        // Fetch selected items
        $barang = Barang::whereIn('id', $selected)->get();

        // Load view and generate PDF
        $pdf = PDF::loadView('barang.print', compact('barang'));

        return $pdf->download('barang_selected.pdf');
    }
}
