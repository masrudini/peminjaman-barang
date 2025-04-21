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
            width: 25px;
            /* Adjust the size of the QR code image */
            height: auto;
            /* Maintain aspect ratio */
        }

    </style>
</head>

<body>
    <h1>Data Barang</h1>

    <table>
        <tbody>
            @foreach ($barang as $b)
            <tr>
                <td style="font-size: 10px;">{{ $loop->iteration }}</td>
                <td style="font-size: 10px;">{{ $b->kode_barang }}</td>
                <td class="align-middle">
                    @if ($b->qr_code)
                    <img src="{{ public_path('storage/' . $b->qr_code) }}" alt="QR Code" style="width: 25px;">
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
