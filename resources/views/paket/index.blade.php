@extends('layouts.admin')

@section('title', 'Data Paket')
@section('dashboard_active', 'active')

@section('content')
<div class="container">
    <h2>Data Paket</h2>

    <!-- Form Pencarian -->
    <form action="{{ route('paket.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari paket..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </form>

    <a href="{{ route('paket.create') }}" class="btn btn-primary mb-3">Tambah Paket</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(request('search'))
        <div class="alert alert-info">
            Menampilkan hasil untuk: <strong>{{ request('search') }}</strong>
        </div>
    @endif

    <table class="table table-bordered">
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
            @forelse ($pakets as $index => $paket)
            <tr>
                <td>{{ $pakets->firstItem() + $index }}</td>
                <td>{{ $paket->nama_paket }}</td>
                <td>{{ $paket->kecepatan }} Mbps</td>
                <td>Rp {{ number_format($paket->harga, 0, ',', '.') }}</td>
                <td>{{ $paket->kategori }}</td>
                <td>
                    <a href="{{ route('paket.edit', $paket->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('paket.destroy', $paket->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus paket ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data paket</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $pakets->links() }}
    </div>
</div>
@endsection
