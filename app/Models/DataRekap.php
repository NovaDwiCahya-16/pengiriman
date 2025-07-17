<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataRekap extends Model
{
    protected $table = 'datarekaps'; // Nama tabel

    protected $fillable = [
        'no_faktur', 'tgl_faktur', 'no_sj_mutasi', 'tgl_sj_mutasi', 'nama_konsumen',
        'kecamatan_kirim', 'kota_kirim', 'leasing', 'nama_type', 'warna', 'cabang',
        'supir', 'tgl_kirim', 'stock', 'harga', 'kwitansi', 'konsumen_bayar',
        'keterangan_tambahan', 'tgl_serah_terima_unit', 'pengiriman_leadtime',
        'performance_pengiriman_hari', 'status_pengiriman', 'keterangan_pending',
        'keterangan_lainnya'
    ];

    public $timestamps = true; // Aktifkan kalau tabel punya created_at & updated_at

    // (Opsional) relasi jika kamu ingin kaitkan dengan user
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}