@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container">
    <h2>Edit Pengguna</h2>

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengguna.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Pengguna</label>
            <input type="text" name="nama_user" class="form-control @error('nama_user') is-invalid @enderror" value="{{ old('nama_user', $user->nama_user) }}" required>
            @error('nama_user')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Level</label>
            <select name="level" class="form-control @error('level') is-invalid @enderror" required>
                <option value="admin" {{ old('level', $user->level) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pelanggan" {{ old('level', $user->level) == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            </select>
            @error('level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Password (kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>       

        <div class="mb-3">
            <label for="foto" class="form-label">Foto Profil</label>
            <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*">
            <small class="text-muted">Maksimal 2MB, format: JPG, PNG, JPEG</small>
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div> 

        {{-- Tampilkan foto lama --}}
        @if ($user->foto)
            <div class="mb-3">
                <label>Foto Saat Ini:</label>
                <br>
                <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" width="100">
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
