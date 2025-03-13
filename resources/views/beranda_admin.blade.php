@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang di halaman admin LILIK NET.</p>

    <div class="row mt-4">
        <!-- Pemasukan -->
        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Pemasukan</h5>
                    <h3>Rp 15,000,000</h3>
                </div>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Pengeluaran</h5>
                    <h3>Rp 5,500,000</h3>
                </div>
            </div>
        </div>

        <!-- Saldo -->
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Saldo Saat Ini</h5>
                    <h3>Rp 9,500,000</h3>
                </div>
            </div>
        </div>

        <!-- Jumlah Transaksi -->
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Transaksi</h5>
                    <h3>120</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
