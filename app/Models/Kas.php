<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';

    protected $fillable = [
        'tanggal', 'keterangan', 'kas_masuk', 'kas_keluar'
    ];
}

