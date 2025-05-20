<!DOCTYPE html>
<html>
<head>
    <title>Cetak Tagihan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            background: #fff;
            color: #2c3e50;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 26px;
            color: #1f4e79;
        }
        .invoice-box {
            max-width: 750px;
            margin: auto;
            border: 2px solid #1f4e79;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #b0c4de;
        }
        th {
            background-color: #e6f0fa;
            text-align: left;
            width: 35%;
            color: #1f4e79;
        }
        td {
            background-color: #f9fbfd;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h2>Tagihan Pelanggan <br>Lilik.Net</h2>
        <table>
            <tr>
                <th>Nama</th>
                <td>{{ $tagihan->pelanggan->user->nama_user ?? '-' }}</td>
            </tr>
            <tr>
                <th>Paket</th>
                <td>{{ $tagihan->pelanggan->data_paket->nama_paket ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>Rp{{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Bulan / Tahun</th>
                <td>{{ $tagihan->bulan }} / {{ $tagihan->tahun }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $tagihan->status }}</td>
            </tr>
        </table>
        <div class="footer">
            Lilik.Net - Sistem Manajemen Tagihan &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
<script>window.print();</script>