<!DOCTYPE html>
<html>

<head>
    <title>Data Barang</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        img {
            width: 100px;
            /* Adjust the size of the QR code image */
            height: auto;
            /* Maintain aspect ratio */
        }
    </style>
</head>

<body>
    <h3>Data Laporan Masalah Jaringan Tahun {{ $tahun }}</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelapor</th>
                <th>Kendala Jaringan</th>
                <th>Ruangan</th>
                <th>Waktu Pelaporan</th>
                <th>Foto</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jaringan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kendala_jaringan }}</td>
                    <td>{{ $item->ruangan }}</td>
                    <td>{{ date('d-M-Y', strtotime($item->created_at)) }}</td>
                    <td class="align-middle">
                        @if ($item->foto)
                            <img src="{{ public_path('storage/' . $item->foto) }}" alt="Foto" style="width: 100px;">
                        @else
                            Tidak ada foto
                        @endif
                    </td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
