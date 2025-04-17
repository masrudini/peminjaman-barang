<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Peminjaman Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h4 {
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .content {
            margin: 0 50px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        .table td {
            padding: 5px;
            vertical-align: top;
        }
        .table td:first-child {
            width: 200px;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-section table {
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
        .signature-section td {
            padding: 30px 10px;
            vertical-align: bottom;
        }
        .signature-line {
            display: block;
            margin-bottom: 60px;
            border-bottom: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .signature-name {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h4>FORMULIR PEMINJAMAN BARANG</h4>
    </div>

    <div class="content">
        <p>Yang bertanggung jawab dalam peminjaman barang di bawah ini:</p>

        <div class="section-title">DATA PEMINJAMAN BARANG</div>
        <table class="table">
            <tr>
                <td>Nama</td>
                <td>: {{ $peminjaman->nama }}</td>
            </tr>
            <tr>
                <td>Nomor HP</td>
                <td>: {{ $peminjaman->nomor_hp }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>: {{ $peminjaman->unit_kerja }}</td>
            </tr>
            <tr>
                <td>Kegiatan</td>
                <td>: {{ $peminjaman->kegiatan }}</td>
            </tr>
            <tr>
                <td>Barang Dipinjam</td>
                <td>: {{ $peminjaman->barang_dipinjam }}</td>
            </tr>
            <tr>
                <td>Waktu Peminjaman</td>
                <td>: {{ $peminjaman->waktu_peminjaman }}</td>
            </tr>
            <tr>
                <td>Waktu Pengembalian</td>
                <td>: {{ $peminjaman->waktu_pengembalian }}</td>
            </tr>
        </table>

        <div class="section-title">PENGAJUAN PERMOHONAN PEMINJAMAN BARANG</div>
        <p>Rincian Data yang dibutuhkan:</p>
        <table class="table">
            <tr>
                <td style="text-align: center;">Unit SISDA</td>
                <td style="text-align: center;">Peminjam Barang</td>
            </tr>
            <tr>
                <td class="signature-line"></td>
                <td class="signature-line">
                    <img src="{{ $peminjaman->ttd_peminjam }}" alt="Tanda Tangan" style="max-height: 100px; max-width: 100%;">
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">Team SISDA</td>
                <td style="text-align: center;">{{ $peminjaman->nama }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
