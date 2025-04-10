<?php

namespace App\Models;

use App\Http\Controllers\PaketController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'tb_pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'nama_pelanggan',
        'alamat',
        'no_telp',
        'paket',  
    ];

    public function user()
    {
        return $this->hasOne(Users::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function data_paket()
    {
        return $this->belongsTo(Paket::class, 'paket', 'id'); 
    }

}
