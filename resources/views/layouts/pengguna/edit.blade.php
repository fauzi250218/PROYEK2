@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container">
    <h2>Edit Pengguna</h2>
    <form action="{{ route('pengguna.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Pengguna</label>
            <input type="text" name="nama_user" class="form-control" value="{{ $user->nama_user }}" required>
        </div>
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
        </div>
        <div class="mb-3">
            <label>Level</label>
            <select name="level" class="form-control" required>
                <option value="admin" {{ $user->level == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pelanggan" {{ $user->level == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Password (kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="form-control">
        </div>        
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
