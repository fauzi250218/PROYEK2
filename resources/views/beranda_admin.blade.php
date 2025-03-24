@extends('layouts.admin')

@section('title', 'Dashboard')
@section('dashboard_active', 'active')

@section('content')
<div class="container mt-4">
    <div class="row mt-3">
        <!-- Kartu Pemasukan -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-lg bg-success text-white">
                <div class="card-body d-flex flex-column align-items-center">
                    <i class="fas fa-wallet fa-2x mb-2"></i>
                    <h6 class="fw-semibold">Total Pemasukan</h6>
                    <h5 class="fw-bold">Rp 15,000,000</h5>
                </div>
            </div>
        </div>

        <!-- Kartu Pengeluaran -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-lg bg-danger text-white">
                <div class="card-body d-flex flex-column align-items-center">
                    <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                    <h6 class="fw-semibold">Total Pengeluaran</h6>
                    <h5 class="fw-bold">Rp 5,500,000</h5>
                </div>
            </div>
        </div>

        <!-- Kartu Saldo -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-lg bg-primary text-white">
                <div class="card-body d-flex flex-column align-items-center">
                    <i class="fas fa-piggy-bank fa-2x mb-2"></i>
                    <h6 class="fw-semibold">Saldo Saat Ini</h6>
                    <h5 class="fw-bold">Rp 9,500,000</h5>
                </div>
            </div>
        </div>

        <!-- Kartu Jumlah Transaksi -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-lg bg-warning text-dark">
                <div class="card-body d-flex flex-column align-items-center">
                    <i class="fas fa-exchange-alt fa-2x mb-2"></i>
                    <h6 class="fw-semibold">Jumlah Transaksi</h6>
                    <h5 class="fw-bold">120</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
