@extends('layouts.admin')
@section('title', 'Bayar Tagihan')

@section('content')
<div class="container">
    <h2 class="mb-4">Bayar Tagihan</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Informasi Tagihan</h5>
            <p><strong>Nama Pelanggan:</strong> {{ $tagihan->pelanggan->user->nama_user }}</p>
            <p><strong>Paket:</strong> {{ $tagihan->pelanggan->data_paket->nama_paket ?? '-' }}</p>
            <p><strong>Jumlah Tagihan:</strong> Rp{{ number_format($tagihan->jumlah, 0, ',', '.') }}</p>
            <p><strong>Bulan:</strong> {{ $namaBulan[$tagihan->bulan] ?? $tagihan->bulan }}</p>
            <p><strong>Tahun:</strong> {{ $tagihan->tahun }}</p>

            <div class="mt-4">
                <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
                <a href="{{ route('tagihan.index') }}" class="btn btn-secondary ms-2">Kembali</a>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay('{{ $snap_token }}', {
        onSuccess: function(result){
            window.location.href = "/tagihan/{{ $tagihan->id }}/bayar/sukses";
        },
        onPending: function(result){
            alert("Menunggu pembayaran");
        },
        onError: function(result){
            alert("Pembayaran gagal");
        },
        onClose: function(){
            alert("Anda menutup tanpa menyelesaikan pembayaran");
        }
    });
});
</script>
@endsection
