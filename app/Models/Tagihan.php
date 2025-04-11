<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    
    protected $table = 'tb_tagihan';
    protected $primaryKey = 'id';
    protected $fillable = ['id_pelanggan', 'bulan', 'tahun', 'jumlah', 'status'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public static function getNamaBulan($angka)
    {
        $bulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];
        return $bulan[$angka] ?? '';
    }
}
