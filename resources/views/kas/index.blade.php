@extends('layouts.admin')
@section('title', 'Data Kas')

@section('content')
<h2>Data Kas Masuk dan Keluar</h2>

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

<a href="{{ route('kas.create') }}" class="btn btn-primary mb-3">Tambah Kas</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Kas Masuk</th>
            <th>Kas Keluar</th>
            <th>Ubah</th>
            <th>Hapus</th>
        </tr>
    </thead>
    <tbody>
        @forelse($kas as $index => $item)
            <tr>
                <td>{{ $kas->firstItem() + $index }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{{ $item->keterangan }}</td>
                <td class="text-end">Rp{{ number_format($item->kas_masuk, 0, ',', '.') }}</td>
                <td class="text-end">Rp{{ number_format($item->kas_keluar, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('kas.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                </td>
                <td>
                    <form action="{{ route('kas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada data kas.</td>
            </tr>
        @endforelse

        <tr class="fw-bold bg-light">
            <td colspan="3" class="text-center">Total</td>
            <td class="text-end">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</td>
            <td class="text-end">Rp{{ number_format($totalKeluar, 0, ',', '.') }}</td>
            <td colspan="2"></td>
        </tr>
        <tr class="fw-bold bg-light">
            <td colspan="3" class="text-center">Saldo</td>
            <td colspan="4" class="text-end">Rp{{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<div class="mt-3">
    {{ $kas->links() }}
</div>
@endsection
