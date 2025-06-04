<?php

// File: app/Models/Users.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;  // Menambahkan HasApiTokens untuk Sanctum

class Users extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;  // Menambahkan HasApiTokens

    protected $table = 'tb_user';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'username',
        'nama_user',
        'email',       // Pastikan email juga disertakan
        'password',
        'level',
        'foto',
    ];

    // Menyembunyikan password untuk keamanan
    protected $hidden = [
        'password',
    ];

    /**
     * Mendefinisikan relasi 'pelanggan' (satu pengguna memiliki satu pelanggan).
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan'); // 'id_pelanggan' adalah foreign key di tabel 'tb_user'
    }
}