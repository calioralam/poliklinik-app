<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;
    protected $table = 'obat';

    protected $fillable = [
        'nama_obat',
        'kemasan',
        'harga',
        'stok',
    ];

     public function kurangiStok($jumlah = 1)
    {
        if ($this->stok >= $jumlah) {
            $this->decrement('stok', $jumlah);
            return true;
        }
        return false; // Stok tidak cukup
    }

    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }
}
