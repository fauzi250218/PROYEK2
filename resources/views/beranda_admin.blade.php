@extends('layouts.admin')
@section('title', 'Beranda Admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Selamat Datang, {{ $user->nama_user }}</h2>

    <div class="row">
        {{-- Total Pemasukan --}}
        <div class="col-md-3 mb-3">
            <a href="{{ route('kas.index') }}" class="text-decoration-none" data-bs-toggle="tooltip" title="Lihat detail pemasukan">
                <div class="card shadow-sm border-start border-success border-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Pemasukan</h6>
                                <h4 class="text-success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h4>
                            </div>
                            <i class="bi bi-cash-stack fs-2 text-success"></i>
                        </div>
                        {{-- Progress Bar --}}
                        <div class="progress mt-3">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($totalMasuk / $totalTargetKas) * 100 }}%" aria-valuenow="{{ ($totalMasuk / $totalTargetKas) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="d-block text-muted mt-2">Progres Pemasukan Bulan Ini</small>
                    </div>
                </div>
            </a>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="col-md-3 mb-3">
            <a href="{{ route('kas.index') }}" class="text-decoration-none" data-bs-toggle="tooltip" title="Lihat detail pengeluaran">
                <div class="card shadow-sm border-start border-danger border-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Pengeluaran</h6>
                                <h4 class="text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h4>
                            </div>
                            <i class="bi bi-cash-coin fs-2 text-danger"></i>
                        </div>
                        {{-- Progress Bar --}}
                        <div class="progress mt-3">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($totalKeluar / $totalTargetKas) * 100 }}%" aria-valuenow="{{ ($totalKeluar / $totalTargetKas) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="d-block text-muted mt-2">Progres Pengeluaran Bulan Ini</small>
                    </div>
                </div>
            </a>
        </div>

        {{-- Saldo --}}
        <div class="col-md-3 mb-3">
            <a href="#" class="text-decoration-none" data-bs-toggle="tooltip" title="Saldo saat ini">
                <div class="card shadow-sm border-start border-primary border-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Saldo Saat Ini</h6>
                                <h4 class="text-primary">Rp {{ number_format($saldo, 0, ',', '.') }}</h4>
                            </div>
                            <i class="bi bi-wallet2 fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Transaksi Lunas --}}
        <div class="col-md-3 mb-3">
            <a href="{{ route('tagihan.index') }}" class="text-decoration-none" data-bs-toggle="tooltip" title="Jumlah transaksi yang sudah lunas">
                <div class="card shadow-sm border-start border-warning border-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Transaksi Lunas</h6>
                                <h4 class="text-warning">{{ $jumlahTransaksiLunas }}</h4>
                            </div>
                            <i class="bi bi-check-circle fs-2 text-warning"></i>
                        </div>
                        <small class="d-block text-muted mt-2">Dari total transaksi: {{ number_format(($jumlahTransaksiLunas / ($jumlahTransaksiLunas + $jumlahTransaksiBelumLunas)) * 100, 2) }}%</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Grafik Pemasukan dan Pengeluaran --}}
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Grafik Pemasukan dan Pengeluaran</h5>
            <div style="height: 100px;">
                <canvas id="kasChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = @json($labels);
        const kasMasuk = @json($totalMasukPerBulan);
        const kasKeluar = @json($totalKeluarPerBulan);

        // Grafik Pemasukan dan Pengeluaran
        const ctx = document.getElementById('kasChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: kasMasuk,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 128, 0, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Pengeluaran',
                        data: kasKeluar,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik Transaksi Lunas vs Belum Lunas
        const transaksiPieChart = document.getElementById('transaksiPieChart').getContext('2d');
        new Chart(transaksiPieChart, {
            type: 'pie',
            data: {
                labels: ['Lunas', 'Belum Lunas'],
                datasets: [{
                    data: [{{ $jumlahTransaksiLunas }}, {{ $jumlahTransaksiBelumLunas }}],
                    backgroundColor: ['#28a745', '#dc3545'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.raw + ' transaksi';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection