<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Tagihan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
        }

        .invoice-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        .header-title {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 16px;
            margin-bottom: 30px;
        }

        .header-title h2 {
            margin: 0;
            color: #007bff;
            font-size: 28px;
        }

        .header-title p {
            margin: 4px 0 0;
            color: #555;
            font-size: 14px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table th, .info-table td {
            padding: 14px 16px;
            border: 1px solid #ddd;
            font-size: 15px;
        }

        .info-table th {
            background-color: #007bff;
            color: white;
            text-align: left;
            width: 35%;
        }

        .status-label {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            color: white;
        }

        .lunas {
            background-color: #28a745;
        }

        .belum {
            background-color: #dc3545;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
                border: none;
                padding: 0;
            }

            .footer {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header-title">
            <h2>Invoice Tagihan</h2>
            <p>{{ now()->format('d F Y, H:i') }}</p>
        </div>

        <table class="info-table">
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
                <td>
                    <span class="status-label {{ strtolower($tagihan->status) === 'lunas' ? 'lunas' : 'belum' }}">
                        {{ ucfirst($tagihan->status) }}
                    </span>
                </td>
            </tr>
        </table>

        <div class="footer">
            Terima kasih telah menggunakan layanan internet kami.<br>
            Jika ada pertanyaan, silakan hubungi admin melalui WhatsApp.
        </div>
    </div>
</body>
</html>
<script>window.print();</script>
