<!DOCTYPE html>
<html>

<head>
    <title>Data Barang</title>
    <style>
        table {
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
        }

        img {
            width: 30px;
            height: 30px;
        }

    </style>
</head>

<body>
    <h1>Data Barang</h1>
    <table>
        <tbody>
            @foreach ($barang as $b)
            <tr>
                <td style="text-align: center; font-size: 12px; white-space: nowrap; display: flex; align-items: center; justify-content: center;">
                    {{ $b->kode_barang }}
                    <img src="{{ public_path('storage/' . $b->qr_code) }}" alt="QR Code" style="vertical-align: middle; margin-left: 5px;">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
