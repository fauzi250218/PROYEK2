<!-- resources/views/laporan/laporan_form.blade.php -->
@extends('layouts.admin')
@section('title', 'Laporan Kas Masuk & Keluar')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">Rekap Kas Masuk dan Keluar</div>
        <div class="card-body">
            <form action="{{ route('kas.laporan.cetak') }}" method="GET" target="_blank">
                <div class="form-group">
                    <label for="tanggal_awal"><strong>Tanggal Awal</strong></label>
                    <input type="date" name="tanggal_awal" class="form-control" required>
                </div>
                <div class="form-group mt-3">
                    <label for="tanggal_akhir"><strong>Tanggal Akhir</strong></label>
                    <input type="date" name="tanggal_akhir" class="form-control" required>
                </div>
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-print"></i> Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
