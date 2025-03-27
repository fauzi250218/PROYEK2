@extends('layouts.admin')

@section('title', 'Data Pengguna')
@section('dashboard_active', 'active')

@section('content')
<div class="container">
    <h2>Data Pengguna</h2>
    <a href="{{ route('pengguna.create') }}" class="btn btn-primary">Tambah Pengguna</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Pengguna</th>
                <th>Username</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if ($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto" width="50" height="50" class="rounded-circle">
                    @else
                        <i class="fas fa-user-circle fa-2x"></i>
                    @endif
                </td>
                <td>{{ $user->nama_user }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ ucfirst($user->level) }}</td>
                <td>
                    <a href="{{ route('pengguna.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?');">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
