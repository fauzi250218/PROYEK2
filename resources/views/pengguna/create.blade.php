@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="container">
    <h2>Tambah Pengguna</h2>

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

    <form action="{{ route('pengguna.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Nama Pengguna</label>
            <input type="text" name="nama_user" class="form-control @error('nama_user') is-invalid @enderror" required value="{{ old('nama_user') }}">
            @error('nama_user')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" required value="{{ old('username') }}">
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
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
        
        <div class="mb-3">
            <label>Level</label>
            <select name="level" class="form-control @error('level') is-invalid @enderror" required>
                <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pelanggan" {{ old('level') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            </select>
            @error('level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
