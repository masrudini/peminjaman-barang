<?php

namespace App\Http\Controllers;

use App\Models\PeminjamRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangan = Ruangan::all();
        return view('ruangan.index', compact('ruangan'));
    }

    public function peminjamanRuangan()
    {
        $search = request()->query('search');

        if ($search) {
            $peminjamans = PeminjamRuangan::query()
                ->where('nama', 'like', "%{$search}%")
                ->orWhere('no_hp', 'like', "%{$search}%")
                ->orWhere('kegiatan', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%")
                ->orWhere('tanggal_pinjam', 'like', "%{$search}%")
                ->orWhere('tanggal_selesai', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%")
                ->orWhereRelation('ruangan', 'nama', 'like', "%{$search}%")
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $peminjam_ruangan = PeminjamRuangan::paginate(10);
        return view('ruangan.peminjaman', compact('peminjam_ruangan', 'search'));
    }

    public function storeRuangan(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'required'
        ]);
        Ruangan::create([
            'nama' => $request->nama_ruangan,
            'image' => $request->image->store('ruangan'),
            'deskripsi' => $request->deskripsi
        ]);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function updateRuangan(Request $request, $id)
    {
        $request->validate([
            'nama_ruangan' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'deskripsi' => 'required'
        ]);

        $ruangan = Ruangan::find($id);
        $ruangan->nama = $request->nama_ruangan;
        if ($request->hasFile('image')) {
            Storage::delete($ruangan->image);
            $ruangan->image = $request->image->store('ruangan');
        }
        $ruangan->deskripsi = $request->deskripsi;
        $ruangan->save();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diupdate');
    }

    public function deleteRuangan($id)
    {
        $ruangan = Ruangan::find($id);
        Storage::delete($ruangan->image);
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus');
    }

    public function deletePeminjamanRuangan($id)
    {
        $peminjaman = PeminjamRuangan::find($id);
        $peminjaman->delete();
        return redirect()->route('ruangan.peminjaman')->with('success', 'Peminjaman berhasil dihapus');
    }

    public function updatePeminjamanRuangan(Request $request, $id)
    {
        $peminjaman = PeminjamRuangan::find($id);
        $nama_ruangan = $peminjaman->ruangan->nama;
        $peminjaman->status = $request->status;

        $peminjaman->keterangan = $request->keterangan;

        if ($request->status == 'Diterima') {
            FonnteController::sendPeminjaman($peminjaman->no_hp, "Peminjaman " . $nama_ruangan .  " pada tanggal " . date('d-m-Y', strtotime($peminjaman->tanggal_pinjam)) . " (" . $peminjaman->jam_mulai . " - " . $peminjaman->jam_selesai . ") " . "telah diterima.");
        } elseif ($request->status == 'Ditolak') {
            FonnteController::sendPeminjaman($peminjaman->no_hp, "Mohon Maaf\nPeminjaman " . $nama_ruangan . " pada tanggal " . date('d-m-Y', strtotime($peminjaman->tanggal_pinjam)) . " (" . $peminjaman->jam_mulai . " - " . $peminjaman->jam_selesai . ") " . "telah ditolak karena:\n" . $peminjaman->keterangan . ".");
        }

        $peminjaman->save();
        return redirect()->route('ruangan.peminjaman')->with('success', 'Peminjaman berhasil diupdate');
    }
}
