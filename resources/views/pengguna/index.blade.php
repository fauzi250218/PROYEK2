@extends('layouts.admin')

@section('title', 'Data Pengguna')
@section('dashboard_active', 'active')

@section('content')
<div class="container">
    <h2>Data Pengguna</h2>

    <!-- Form Pencarian -->
    <form action="{{ route('pengguna.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari pengguna...">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </form>

    @if(request('search'))
        <div class="alert alert-info">
            Menampilkan hasil pencarian untuk: <strong>{{ request('search') }}</strong>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('pengguna.create') }}" class="btn btn-primary mb-3">Tambah Pengguna</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama User</th>
                <th>Username</th>
                <th>Level</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $u)
            <tr>
                <td>{{ $users->firstItem() + $index }}</td>
                <td>
                    <img src="{{ asset('storage/' . $u->foto) }}" alt="Foto" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                </td>                              
                <td>{{ $u->nama_user }}</td>
                <td>{{ $u->username }}</td>
                <td>{{ $u->level }}</td>
                <td>
                    <a href="{{ route('pengguna.edit', $u->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('pengguna.destroy', $u->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data pengguna</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus pengguna ini?");
    }
</script>
@endsection
