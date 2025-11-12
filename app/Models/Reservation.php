<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'nama_tamu',
        'no_identitas',
        'check_in',
        'check_out',
        'jumlah_tamu',
        'total_harga',
        'denda',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_harga' => 'decimal:2',
        'denda' => 'decimal:2',
    ];

    protected $appends = [
        'lama_inap',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function housekeepingCheck()
    {
        return $this->hasOne(HousekeepingCheck::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['menginap', 'menunggu_pengecekan']);
    }

    public function getLamaInapAttribute(): int
    {
        $checkIn = $this->check_in instanceof Carbon ? $this->check_in : Carbon::parse($this->check_in);
        $checkOut = $this->check_out instanceof Carbon ? $this->check_out : Carbon::parse($this->check_out);

        return max($checkIn->diffInDays($checkOut), 1);
    }

    public function getSubtotalAttribute(): float
    {
        return (float) ($this->room?->harga_per_malam ?? 0) * $this->lama_inap;
    }

    public function getGrandTotalAttribute(): float
    {
        return $this->subtotal + (float) $this->denda;
    }
}
