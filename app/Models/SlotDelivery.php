<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_pengiriman',
        'slot_pengiriman',
        'permintaan_kirim',
        'over_sisa',
    ];

    protected $casts = [
        'tanggal_pengiriman' => 'date',
    ];
}
