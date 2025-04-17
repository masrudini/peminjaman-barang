<?php

namespace App\Http\Controllers;

use PDF;
use CURLFile;
use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Rules\ReCaptcha;
use App\Models\Peminjaman;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BarangDiPinjam;
use App\Models\KategoriBarang;
use App\Models\BarangDiBerikan;
use App\Models\GambarPeminjamanBarang;
use App\Models\PeminjamRuangan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class PeminjamanController extends Controller
{
    public function create()
    {
        // Get all categories
        $kategori = KategoriBarang::all();

        // Pass categories to the view
        return view('peminjaman.create', compact('kategori'));
    }

    public function ruangan()
    {
        $ruangan = Ruangan::all();
        $ruangan_tersedia = Ruangan::where('status', 'Tersedia')->get();
        if (request()->has('search')) {
            $jadwal = PeminjamRuangan::where('tanggal_pinjam', date('d-m-Y', strtotime(request('search'))))->whereIn('status', ['Diterima', 'Selesai'])->get();
        } else {
            $jadwal = PeminjamRuangan::where('tanggal_pinjam', date('d-m-Y'))->whereIn('status', ['Diterima', 'Selesai'])->get();
        }

        return view('peminjaman.ruangan', compact('ruangan', 'jadwal', 'ruangan_tersedia'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required',
            'nomor_hp' => 'required',
            'unit_kerja' => 'required',
            'kegiatan' => 'required',
            'waktu_peminjaman' => 'required',
            'waktu_pengembalian' => 'required',
            'ttd_peminjam' => 'required',
            'kategori_barang_id' => 'required|array',
            'jumlah' => 'required|array',
            // 'g-recaptcha-response' => ['required', new ReCaptcha],
        ]);

        // Handle signature
        $image = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->ttd_peminjam);
        $imageName = Str::random(10) . '.png';
        File::put(storage_path('app/public/ttd/' . $imageName), base64_decode($image));

        // Create a new Peminjaman record
        $peminjaman = Peminjaman::create([
            'nama' => $request->nama,
            'nomor_hp' => $request->nomor_hp,
            'unit_kerja' => $request->unit_kerja,
            'kegiatan' => $request->kegiatan,
            'waktu_peminjaman' => $request->waktu_peminjaman,
            'waktu_pengembalian' => $request->waktu_pengembalian,
            'ttd_peminjam' => 'ttd/' . $imageName,
        ]);

        // Validate array counts
        if (count($request->kategori_barang_id) !== count($request->jumlah)) {
            return back()->withErrors(['message' => 'Jumlah kategori dan jumlah barang harus sesuai.']);
        }

        // Create related BarangDiPinjam records
        foreach ($request->kategori_barang_id as $index => $kategori_id) {
            BarangDiPinjam::create([
                'peminjaman_id' => $peminjaman->id,
                'kategori_barang_id' => $kategori_id,
                'jumlah' => $request->jumlah[$index],
            ]);
        }

        FonnteController::send("Terdapat peminjaman barang, dari " . $request->nama . ".\n\nSilahkan cek " . env("APP_URL") . "/login" .  " untuk detail lebih lanjut.");

        return redirect()->route('peminjaman.create')->with('success', 'Data peminjaman berhasil disimpan.');
    }

    public function storeRuangan(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kegiatan' => 'required',
            'no_hp' => 'required',
            'tanggal_pinjam' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan_id' => 'required',
        ]);

        foreach ($request->tanggal_pinjam as $index => $tanggal) {
            $peminjamRuangan = PeminjamRuangan::create([
                'nama' => $request->nama,
                'kegiatan' => $request->kegiatan,
                'no_hp' => $request->no_hp,
                'tanggal_pinjam' => $tanggal,
                'jam_mulai' => $request->jam_mulai[$index],
                'jam_selesai' => $request->jam_selesai[$index],
                'ruangan_id' => $request->ruangan_id,
            ]);
        }
        FonnteController::send("Terdapat peminjaman ruangan, dari " . $request->nama . ".\n\nSilahkan cek " . env("APP_URL") . "/login" .  " untuk detail lebih lanjut.");

        return redirect()->route('peminjaman.ruangan')->with('success', 'Data peminjaman ruangan berhasil disimpan.');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $peminjamans = Peminjaman::query()
                ->where('nama', 'like', "%{$search}%")
                ->orWhere('nomor_hp', 'like', "%{$search}%")
                ->orWhere('unit_kerja', 'like', "%{$search}%")
                ->orWhere('kegiatan', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%")
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $peminjamans = Peminjaman::paginate(10);
        }
        $barangs = Barang::where('status', 'tersedia')->get();

        $gambar_sebelum = GambarPeminjamanBarang::select('peminjaman_id', 'gambar_sebelum')->where('gambar_sebelum', '!=', null)->get();
        $gambar_sesudah = GambarPeminjamanBarang::select('peminjaman_id', 'gambar_sesudah')->where('gambar_sesudah', '!=', null)->get();

        // untuk tom select
        $peminjaman = Peminjaman::all();
        $barang = Barang::all();

        return view('barang.peminjaman', compact('search', 'peminjamans', 'barangs', 'peminjaman', 'gambar_sebelum', 'gambar_sesudah'));
    }

    public function downloadPdf($id)
    {
        $peminjaman = Peminjaman::with('barangDiPinjam.kategoriBarang')->findOrFail($id);

        Settings::setOutputEscapingEnabled(true);
        $templateProcessor = new TemplateProcessor('storage/template/Form_Peminjaman.docx');

        $nama_kategori = '';
        $barang_diberikan = '';
        foreach ($peminjaman->barangDiPinjam as $item) {
            if ($item->kategoriBarang && $item->kategoriBarang->nama_kategori) {
                $nama_kategori .= "- " . $item->kategoriBarang->nama_kategori . "(" . $item->jumlah . ")" . "</w:t><w:br/><w:t>";
            }
        }

        foreach ($peminjaman->barangDiBerikan as $item) {
            if ($item->barang && $item->barang->nama_barang) {
                $barang_diberikan .= "- " . $item->barang->nama_barang . "</w:t><w:br/><w:t>";
            }
        }

        $templateProcessor->setValues([
            'nama' => $peminjaman->nama,
            'nomor_hp' => $peminjaman->nomor_hp,
            'unit_kerja' => $peminjaman->unit_kerja,
            'kegiatan' => $peminjaman->kegiatan,
            'nama_kategori' => $nama_kategori,
            'barang_diberikan' => $barang_diberikan,
            'waktu_peminjaman' => Carbon::parse($peminjaman->waktu_peminjaman)->format('d F Y'),
            'waktu_pengembalian' => Carbon::parse($peminjaman->waktu_pengembalian)->format('d F Y'),
        ]);

        $templateProcessor->setImageValue('ttd_peminjam', array(
            'path' => storage_path('app/public/') . $peminjaman->ttd_peminjam,
            'width' => 100,
            'height' => 100,
            'ratio' => false,
        ));

        $path = 'storage/surat_peminjaman/' . $peminjaman->id . '.docx';
        $templateProcessor->saveAs($path);

        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://38.210.85.214:30000/api/doc-to-pdf',
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_POSTFIELDS => array(
                    'file' => new CURLFile($path)
                ),
            ));

            $response = curl_exec($curl);
            Storage::put('surat_peminjaman/' . $peminjaman->id . '.pdf', $response);
            Storage::delete('surat_peminjaman/' . $peminjaman->id . '.docx');

            curl_close($curl);
        } catch (\Throwable $th) {
            Log::error("Error while converting to PDF: " . $th->getMessage());
            return redirect()->route('peminjaman.index')->with('error', 'Gagal membuat formulir permohonan');
        }

        $namaFileCustom = 'surat_peminjaman_' . $peminjaman->nama . '_' . $peminjaman->waktu_peminjaman . '.pdf';
        return Storage::download('surat_peminjaman/' . $peminjaman->id . '.pdf', $namaFileCustom);
    }

    public function edit($id)
    {
        $barang = Barang::all();
        $peminjaman = Peminjaman::findOrFail($id);

        return view('barang.edit', compact('peminjaman', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($request->status == 'Diterima') {
            foreach ($peminjaman->barangDiBerikan as $barang) {
                $barang = Barang::findOrFail($barang->barang_id);
                $barang->status = 'Tersedia';
                $barang->save();
            }
            BarangDiBerikan::where('peminjaman_id', $peminjaman->id)->delete();
            if ($request->barangs) {
                foreach ($request->barangs as $barang) {
                    $barang = Barang::findOrFail($barang);
                    $barang->status = 'Tidak tersedia';
                    $barang->save();
                    BarangDiBerikan::create([
                        'peminjaman_id' => $peminjaman->id,
                        'barang_id' => $barang->id,
                    ]);
                }
                $peminjaman->keterangan = $request->keterangan;
                $peminjaman->save();
                FonnteController::sendPeminjaman($peminjaman->nomor_hp, "Peminjaman barang anda sudah diterima oleh admin.");
            }

            // save multiple images
            if ($request->gambar_sebelum) {
                foreach ($request->gambar_sebelum as $index => $gambar_sebelum) {
                    GambarPeminjamanBarang::create([
                        'peminjaman_id' => $peminjaman->id,
                        'gambar_sebelum' => $request->gambar_sebelum[$index]->store('gambar_peminjaman', 'public'),
                    ]);
                }
            }
        }

        if ($request->status == 'Ditolak' || $request->status == 'Pending') {
            if ($peminjaman->barangDiBerikan) {
                BarangDiBerikan::where('peminjaman_id', $peminjaman->id)->delete();
                foreach ($peminjaman->barangDiBerikan as $barang) {
                    $barang = Barang::findOrFail($barang->barang_id);
                    $barang->status = 'Tersedia';
                    $barang->save();
                }
            }
            if ($request->status == 'Ditolak') {
                $peminjaman->keterangan = $request->keterangan;
                $peminjaman->save();
                FonnteController::sendPeminjaman($peminjaman->nomor_hp, "Mohon maaf peminjaman barang anda ditolak \nDikarenakan : " . $peminjaman->keterangan);
            }
        }

        if ($request->status == 'Selesai') {
            foreach ($peminjaman->barangDiBerikan as $barang) {
                $barang = Barang::findOrFail($barang->barang_id);
                $barang->status = 'Tersedia';
                $barang->save();
            }

            if ($request->gambar_sesudah) {
                foreach ($request->gambar_sesudah as $index => $gambar_sesudah) {
                    GambarPeminjamanBarang::create([
                        'peminjaman_id' => $peminjaman->id,
                        'gambar_sesudah' => $request->gambar_sesudah[$index]->store('gambar_peminjaman', 'public'),
                    ]);
                }
            }
        }

        $peminjaman->status = $request->status;
        $peminjaman->save();

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    // delete peminjaman
    public function delete($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->ttd_peminjaman && Storage::exists($peminjaman->ttd_peminjaman)) {
            Storage::delete($peminjaman->ttd_peminjaman);
        }

        foreach ($peminjaman->gambarPeminjamanBarang as $gambar) {
            if ($gambar->gambar_sebelum && Storage::exists($gambar->gambar_sebelum)) {
                Storage::delete($gambar->gambar_sebelum);
            }
            if ($gambar->gambar_sesudah && Storage::exists($gambar->gambar_sesudah)) {
                Storage::delete($gambar->gambar_sesudah);
            }
        }

        $peminjaman->barangDiPinjam()->delete();
        $peminjaman->barangDiBerikan()->delete();
        $peminjaman->delete();


        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
