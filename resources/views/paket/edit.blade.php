@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Paket</h2>
    <form action="{{ route('paket.edit', $paket->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Paket</label>
            <input type="text" name="nama_paket" class="form-control" value="{{ $paket->nama_paket }}" required>
        </div>
        <div class="mb-3">
            <label>Kecepatan (Mbps)</label>
            <input type="number" name="kecepatan" class="form-control" value="{{ $paket->kecepatan }}" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ $paket->harga }}" required>
        </div>
        <div class="mb-3">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" value="{{ $paket->kategori }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
