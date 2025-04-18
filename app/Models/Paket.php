<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'data_paket';

    protected $fillable = ['nama_paket', 'kecepatan', 'harga', 'kategori'];
}