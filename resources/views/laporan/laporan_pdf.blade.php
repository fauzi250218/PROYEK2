<!-- resources/views/laporan/laporan_pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kas</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Laporan Kas Masuk dan Keluar</h2>
    <p>Periode: {{ $tanggalAwal }} s.d. {{ $tanggalAkhir }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Kas Masuk</th>
                <th>Kas Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kas as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ number_format($item->kas_masuk, 0, ',', '.') }}</td>
                <td>{{ number_format($item->kas_keluar, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="3">Total</th>
                <th>{{ number_format($totalMasuk, 0, ',', '.') }}</th>
                <th>{{ number_format($totalKeluar, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="4">Saldo</th>
                <th>{{ number_format($saldo, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>
