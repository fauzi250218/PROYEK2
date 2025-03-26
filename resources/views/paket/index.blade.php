@extends('layouts.admin')

@section('title', 'Data Paket')
@section('dashboard_active', 'active')

@section('content')
<div class="container">
    <h2>Data Paket</h2>
    <a href="{{ route('paket.create') }}" class="btn btn-primary">Tambah</a>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Paket</th>
                <th>Kecepatan</th>
                <th>Harga</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pakets as $index => $paket)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $paket->nama_paket }}</td>
                <td>{{ $paket->kecepatan }} Mbps</td>
                <td>Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                <td>{{ $paket->kategori }}</td>
                <td>
                    <a href="{{ route('paket.edit', $paket->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('paket.destroy', $paket->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
