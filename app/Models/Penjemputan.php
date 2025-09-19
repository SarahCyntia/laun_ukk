<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjemputan extends Model
{
    protected $table = 'antar_jemput';

    protected $fillable = [
        'id_pesanan',
        'id_pelanggan',
        'tracking_code',
        'nama_pelanggan',
        'no_hp',
        'layanan',
        'alamat_antar',
        'alamat_jemput',
        'status',
        'waktu_antar',
        'waktu_jemput',
        'selesai_at',
        'harga',
        'status_pembayaran',
    ];
}
