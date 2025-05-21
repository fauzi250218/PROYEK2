@extends('layouts.admin')
@section('title', 'Data Tagihan')

@section('content')
<h2>Data Tagihan</h2>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: {!! json_encode(session('success')) !!},
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: {!! json_encode(session('error')) !!},
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endif
<form action="{{ route('tagihan.index') }}" method="GET" class="row g-2 align-items-center mb-3">
    <div class="col-auto">
        <select name="bulan" class="form-select">
            <option value="">-- Pilih Bulan --</option>
            @foreach($namaBulan as $bln => $nama)
                <option value="{{ $bln }}" {{ request('bulan') == $bln ? 'selected' : '' }}>{{ $nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <select name="tahun" class="form-control">
            <option value="">-- Pilih Tahun --</option>
            @foreach($tahunList as $thn)
                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
            @endforeach
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
            <th>Periode</th>
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
            <td>{{ ($namaBulan[$t->bulan] ?? $t->bulan) . ' ' . $t->tahun }}</td>
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
                
                <!-- Hanya tampilkan tombol Bayar jika status tagihan 'Belum Lunas' -->
                @if($t->status == 'Belum Lunas')
                <div class="btn-group">
                    <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Bayar
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('tagihan.manual', $t->id) }}">Bayar Manual (WA)</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('tagihan.midtrans', $t->id) }}">Bayar Otomatis</a>
                        </li>
                    </ul>
                </div>                
                @endif
            
                <a href="{{ route('tagihan.kirimwa', $t->id) }}" class="btn btn-info btn-sm">Kirim WA</a>
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