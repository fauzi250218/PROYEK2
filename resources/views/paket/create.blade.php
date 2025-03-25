@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Tambah Paket</h2>
    <form action="{{ route('paket.store') }}" method="POST">
        @csrf
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <div class="mb-3">
            <label>Nama Paket</label>
            <select name="nama_paket" class="form-control" required>
                <option value="">Pilih Paket</option>
                <option value="Paket Murah">Paket Murah</option>
                <option value="Paket Menengah">Paket Menengah</option>
                <option value="Paket Mewah">Paket Mewah</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Kecepatan (Mbps)</label>
            <input type="number" name="kecepatan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
