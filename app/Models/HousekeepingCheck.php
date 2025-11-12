<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousekeepingCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'housekeeper_id',
        'status',
        'catatan',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function housekeeper()
    {
        return $this->belongsTo(User::class, 'housekeeper_id');
    }
}
