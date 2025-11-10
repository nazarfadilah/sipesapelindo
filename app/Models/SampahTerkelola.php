<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampahTerkelola extends Model
{
    use HasFactory;

    protected $table = 'sampah_terkelolas';
    
    protected $fillable = [
        'id_user',
        'id_lokasi',
        'id_jenis',
        'jumlah_berat',
        'tgl',
        'foto',
    ];

    protected $casts = [
        'tgl' => 'datetime',
        'jumlah_berat' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function lokasiAsal()
    {
        return $this->belongsTo(LokasiAsal::class, 'id_lokasi');
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'id_jenis');
    }
}