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
                                <h4 class="text-warning">{{ $jumlahTransaksi }}</h4>
                            </div>
                            <i class="bi bi-check-circle fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
