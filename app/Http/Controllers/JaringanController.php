<?php

namespace App\Http\Controllers;

use CURLFile;
use App\Models\Jaringan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class JaringanController extends Controller
{
    public function index()
    {
        return view('jaringan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kendala_jaringan' => 'required',
            'ruangan' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            Jaringan::create([
                'nama' => $request->nama,
                'kendala_jaringan' => $request->kendala_jaringan,
                'ruangan' => $request->ruangan,
                'foto' => $request->foto->store('jaringan', 'public'),
            ]);
        } else {
            Jaringan::create([
                'nama' => $request->nama,
                'kendala_jaringan' => $request->kendala_jaringan,
                'ruangan' => $request->ruangan,
            ]);
        }
        return redirect()->route('jaringan.index')->with('success', 'Laporan berhasil dikirim');
    }

    public function admin_jaringan(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $jaringan = Jaringan::where(function ($query) use ($search) {
                $query->where('kendala_jaringan', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%')
                    ->orWhere('ruangan', 'like', '%' . $search . '%');
            })->paginate(10);
        } else {
            $jaringan = Jaringan::paginate(10);
        }
        return view('jaringan.pengaduan_jaringan', compact('jaringan'));
    }

    public function update(Request $request, $id)
    {
        $jaringan = Jaringan::findOrFail($id);
        $jaringan->update([
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('jaringan.admin')->with('success', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $jaringan = Jaringan::findOrFail($id);
        if ($jaringan->foto) {
            Storage::delete($jaringan->foto);
        }
        $jaringan->delete();
        return redirect()->route('jaringan.admin')->with('success', 'Data berhasil dihapus');
    }

    public function cetak(Request $request)
    {

        // Fetch selected items
        $tahun = $request->tahun;
        $jaringan = Jaringan::where('created_at', 'like', '%' . $tahun . '%')->get();

        // Load view and generate PDF
        $pdf = PDF::loadView('jaringan.print', compact('jaringan', 'tahun'));

        return $pdf->download('laporan_jaringan_' . $tahun . '.pdf');
    }

    public function print($id)
    {
        $jaringan = Jaringan::findOrFail($id);

        Settings::setOutputEscapingEnabled(true);
        $templateProcessor = new TemplateProcessor('storage/template/Pengaduan_Jaringan.docx');

        if ($jaringan->foto) {
            $templateProcessor->setValues([
                'nama' => $jaringan->nama,
                'kendala_jaringan' => $jaringan->kendala_jaringan,
                'ruangan' => $jaringan->ruangan,
                'keterangan' => $jaringan->keterangan,
                'created_at' => date('d M Y', strtotime($jaringan->created_at)),
            ]);
            $templateProcessor->setImageValue('foto', array(
                'path' => public_path('storage/') . $jaringan->foto,
                'width' => 50,
                'height' => 50,
                'ratio' => false,
            ));
        } else {
            $templateProcessor->setValues([
                'nama' => $jaringan->nama,
                'kendala_jaringan' => $jaringan->kendala_jaringan,
                'ruangan' => $jaringan->ruangan,
                'keterangan' => $jaringan->keterangan,
                'created_at' => date('d M Y', strtotime($jaringan->created_at)),
                'foto' => '',
            ]);
        }

        $path = 'storage/pengaduan_jaringan/' . $jaringan->id . '.docx';
        $templateProcessor->saveAs($path);

        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://103.154.130.13:30000/api/doc-to-pdf',
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_POSTFIELDS => array(
                    'file' => new CURLFile($path)
                ),
            ));

            $response = curl_exec($curl);
            Storage::put('pengaduan_jaringan/' . $jaringan->id . '.pdf', $response);
            Storage::delete('pengaduan_jaringan/' . $jaringan->id . '.docx');

            curl_close($curl);
        } catch (\Throwable $th) {
            Log::error("Error while converting to PDF: " . $th->getMessage());
            return redirect()->route('jaringan.admin')->with('error', 'Gagal membuat formulir permohonan');
        }

        $namaFileCustom = 'pengaduan_jaringan_' . $jaringan->nama . '.pdf';
        return Storage::download('pengaduan_jaringan/' . $jaringan->id . '.pdf', $namaFileCustom);
    }
}
