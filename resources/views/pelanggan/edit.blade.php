@extends('layouts.admin')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="container">
    <h2>Edit Pelanggan</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST" onsubmit="return confirmPaketSelection();">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                value="{{ old('username', $pelanggan->user->username ?? '') }}" required>
        </div>                
        <div class="mb-3">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $pelanggan->alamat) }}" required>
        </div>

        <div class="mb-3">
            <label>No. Telepon</label>
            <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $pelanggan->no_telp) }}" required>
        </div>

        <div class="mb-3">
            <label>Paket</label>
            <select name="paket" id="paket-select" class="form-control" required>
                <option value="">Pilih Paket</option>
                @foreach($data_paket as $paket)
                    <option value="{{ $paket->id }}"
                        data-harga="{{ $paket->harga }}"
                        {{ old('paket', $pelanggan->paket_id) == $paket->id ? 'selected' : '' }}>
                        {{ $paket->nama_paket }}
                    </option>
                @endforeach
            </select>                    
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="text" id="harga" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    function formatRupiah(angka) {
        return 'Rp ' + angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateHarga() {
        let paketSelect = document.getElementById('paket-select');
        let hargaInput = document.getElementById('harga');
        let selectedOption = paketSelect.options[paketSelect.selectedIndex];
        let harga = selectedOption.getAttribute('data-harga') || '0';
        hargaInput.value = formatRupiah(harga);
    }

    function confirmPaketSelection() {
        let paketSelect = document.getElementById('paket-select');
        if (paketSelect.value === '') {
            alert("Pilih paket terlebih dahulu.");
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('paket-select').addEventListener('change', updateHarga);
        updateHarga(); // untuk isi harga saat reload
    });
</script>
@endsection
