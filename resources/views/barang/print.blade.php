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
    <h1>Data Barang</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>QR Code</th> <!-- Column for the QR Code -->
            </tr>
        </thead>
        <tbody>
            @foreach ($barang as $b)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $b->kode_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td class="align-middle">
                    @if ($b->qr_code)
                    <img src="{{ public_path('storage/' . $b->qr_code) }}" alt="QR Code" style="width: 100px;">
                    @else
                    No QR Code
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
