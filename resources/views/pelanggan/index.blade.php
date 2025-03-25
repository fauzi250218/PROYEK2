@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('dashboard_active', 'active')

@section('content')
<div class="container">
    <h2>Data Pelanggan</h2>
    <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">Tambah Pelanggan</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Username</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
                <th>Paket</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pelanggan as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->nama_user }}</td>
                <td>{{ $p->username }}</td>
                <td>{{ optional($p->pelanggan)->alamat ?? '-' }}</td>
                <td>{{ optional($p->pelanggan)->no_telp ?? '-' }}</td>
                <td>{{ optional($p->pelanggan)->paket ?? '-' }}</td>
                <td>
                    @if ($p->pelanggan)
                        <a href="{{ route('pelanggan.edit', $p->id_pelanggan) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('pelanggan.destroy', $p->id_pelanggan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus pelanggan ini?');">Hapus</button>
                        </form>
                    @else
                        Tidak ada data
                    @endif
                </td>                
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data pelanggan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
