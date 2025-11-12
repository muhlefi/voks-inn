<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'tipe',
        'nominal',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
