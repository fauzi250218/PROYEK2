@extends('layouts.admin')
@section('title', 'Buat Tagihan')

@section('content')
<div class="container">
    <h4>Buat Tagihan Baru</h4>
    <form action="{{ route('tagihan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="bulan" class="form-label">Bulan</label>
            <select name="bulan" class="form-control" required>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}">{{ \App\Models\Tagihan::getNamaBulan(str_pad($i, 2, '0', STR_PAD_LEFT)) }}</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Buat Tagihan</button>
    </form>
</div>
@endsection
