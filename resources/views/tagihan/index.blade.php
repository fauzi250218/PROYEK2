@extends('layouts.admin')
@section('title', 'Data Tagihan')

@section('content')
<h2>Data Tagihan</h2>

@if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif

<form action="{{ route('tagihan.index') }}" method="GET" class="row g-2 align-items-center mb-3">
    <div class="col-auto">
        <select name="bulan" class="form-select">
            <option value="">-- Pilih Bulan --</option>
            @foreach($namaBulan as $bln => $nama)
                <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>
                    {{ $nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <select name="tahun" class="form-select">
            <option value="">-- Pilih Tahun --</option>
            @for($thn = now()->year; $thn >= 2020; $thn--)
                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
            @endfor
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<a href="{{ route('tagihan.create') }}" class="btn btn-primary mb-3">Buat Tagihan Bulan Ini</a>

@if(request('search'))
    <div class="alert alert-info">Menampilkan hasil untuk: <strong>{{ request('search') }}</strong></div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Paket</th>
            <th>Harga</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tagihans as $index => $t)
        <tr>
            <td>{{ $tagihans->firstItem() + $index }}</td>
            <td>{{ optional(optional($t->pelanggan)->user)->nama_user ?? '-' }}</td>
            <td>{{ optional(optional($t->pelanggan)->data_paket)->nama_paket ?? '-' }}</td>
            <td>Rp{{ number_format($t->jumlah, 0, ',', '.') }}</td>
            <td>{{ $namaBulan[$t->bulan] ?? $t->bulan }}</td>
            <td>{{ $t->tahun }}</td>
            <td>
                @if($t->status == 'Lunas')
                    <span class="badge bg-success">Lunas</span>
                @else
                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                @endif
            </td>
            <td>
                @if($t->status == 'Belum Lunas')
                    <form action="{{ route('tagihan.tandaiLunas', $t->id) }}" method="POST" class="d-inline form-tandai-lunas">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">Tandai Lunas</button>
                    </form>
                @endif
                <a href="{{ route('tagihan.cetak', $t->id) }}" class="btn btn-secondary btn-sm" target="_blank">Cetak</a>
                <a href="{{ route('tagihan.kirimwa', $t->id) }}" class="btn btn-info btn-sm" target="_blank">Kirim WA</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">Tidak ada data tagihan.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $tagihans->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.form-tandai-lunas');

        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Tandai sebagai lunas?',
                    text: "Status tagihan akan diubah menjadi Lunas!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, tandai lunas'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
