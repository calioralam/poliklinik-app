<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $table = 'poli'; // nama tabel di database

    protected $fillable = [ 
        'nama_poli',    // kolom yang dapat diisi
        'keterangan',  // kolom yang dapat diisi
    ];

    // relasi dengan model user (dokter)
    public function dokters()
    {
        return $this->hasMany(User::class, 'id_poli');
    }
}
