@extends('layouts.admin')

@section('title', 'Edit Kas')

@section('content')
<div class="container">
    <h2>Edit Data Kas</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kas.update', $kas->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $kas->tanggal }}" required>
        </div>

        <div class="mb-3">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control" value="{{ $kas->keterangan }}" required>
        </div>

        <div class="mb-3">
            <label>Kas Masuk</label>
            <input type="number" name="kas_masuk" class="form-control" value="{{ $kas->kas_masuk }}" min="0">
        </div>

        <div class="mb-3">
            <label>Kas Keluar</label>
            <input type="number" name="kas_keluar" class="form-control" value="{{ $kas->kas_keluar }}" min="0">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('kas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
