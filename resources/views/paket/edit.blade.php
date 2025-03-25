@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Paket</h2>
    <form action="{{ route('paket.update', $paket->id) }}" method="POST">
        @csrf
        @method('PUT')    
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
                <option value="Paket Murah" {{ $paket->nama_paket == 'Paket Murah' ? 'selected' : '' }}>Paket Murah</option>
                <option value="Paket Menengah" {{ $paket->nama_paket == 'Paket Menengah' ? 'selected' : '' }}>Paket Menengah</option>
                <option value="Paket Mewah" {{ $paket->nama_paket == 'Paket Mewah' ? 'selected' : '' }}>Paket Mewah</option>
            </select>
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
