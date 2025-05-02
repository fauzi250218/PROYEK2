<!DOCTYPE html>
<html>
<head>
    <title>Cetak Tagihan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <h2>Tagihan Pelanggan Lilik.Net</h2>
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
</body>
</html>
<script>window.print();</script>