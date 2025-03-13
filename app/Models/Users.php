<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_user'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true; // Pastikan tabel memiliki created_at & updated_at

    protected $fillable = [
        'username',
        'nama_user',
        'password',
        'level',
        'foto',
        'id_pelanggan',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke tabel Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
