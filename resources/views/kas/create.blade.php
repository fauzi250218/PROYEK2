@extends('layouts.admin') {{-- Ganti dengan layout kamu jika beda --}}

@section('content')
<div class="container mt-4">
    <h2>Tambah Data Kas</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('kas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="kas_masuk" class="form-label">Kas Masuk</label>
            <input type="number" name="kas_masuk" class="form-control" min="0">
        </div>
        <div class="mb-3">
            <label for="kas_keluar" class="form-label">Kas Keluar</label>
            <input type="number" name="kas_keluar" class="form-control" min="0">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
