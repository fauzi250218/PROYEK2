<!-- resources/views/laporan/laporan_pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kas</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            color: #2c3e50;
        }
        h2 {
            text-align: center;
            color: #1f4e79;
            margin-bottom: 10px;
        }
        p {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th, td {
            border: 1px solid #b0c4de;
            padding: 8px;
            text-align: center;
        }
        thead th {
            background-color: #e6f0fa;
            color: #1f4e79;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fbfd;
        }
        tfoot th {
            background-color: #dce6f7;
            color: #1f4e79;
        }
    </style>
</head>
<body>
    <h2>Laporan Kas Masuk dan Keluar Lilik.Net</h2>
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
                <td>Rp{{ number_format($item->kas_masuk, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($item->kas_keluar, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th>Rp{{ number_format($totalMasuk, 0, ',', '.') }}</th>
                <th>Rp{{ number_format($totalKeluar, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="4">Saldo</th>
                <th>Rp{{ number_format($saldo, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
