@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="container">
    <h2>Tambah Pengguna</h2>
    <form action="{{ route('pengguna.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Pengguna</label>
            <input type="text" name="nama_user" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Level</label>
            <select name="level" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="pelanggan">Pelanggan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
