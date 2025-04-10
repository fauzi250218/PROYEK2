@extends('layouts.admin')
@section('title', 'Data Pelanggan')

@section('content')
<h2>Data Pelanggan</h2>

<form action="{{ route('pelanggan.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." class="form-control" />
        <button type="submit" class="btn btn-primary">Cari</button>
    </div>
</form>    

<a href="{{ route('pelanggan.create') }}" class="btn btn-primary mb-3">Tambah Pelanggan</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(request('search'))
    <div class="alert alert-info">Menampilkan hasil untuk: <strong>{{ request('search') }}</strong></div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pelanggan</th>
            <th>Username</th>
            <th>Alamat</th>
            <th>No. Telepon</th>
            <th>Paket</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pelanggan as $index => $p)
            <tr>
                <td>{{ $pelanggan->firstItem() + $index }}</td>
                <td>{{ $p->nama_user }}</td>
                <td>{{ $p->username }}</td>
                <td>{{ $p->pelanggan->alamat ?? '-' }}</td>
                <td>{{ $p->pelanggan->no_telp ?? '-' }}</td>
                <td>{{ $p->pelanggan->data_paket->nama_paket ?? '-' }}</td>
                <td>{{ $p->pelanggan->data_paket->harga ?? '-' }}</td>                                                         
                <td>
                    @if($p->pelanggan)
                        <a href="{{ route('pelanggan.edit', $p->pelanggan->id_pelanggan) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pelanggan.destroy', $p->pelanggan->id_pelanggan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    @else
                        <span class="text-muted">Data tidak lengkap</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data pelanggan.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $pelanggan->links() }}
</div>
@endsection
