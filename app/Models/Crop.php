<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'crop_name',
        'planting_date',
        'harvest_date',
        'status',
        'notes',
    ];

    // Cast - dates zitambuliwe kama tarehe
    protected $casts = [
        'planting_date' => 'date',
        'harvest_date' => 'date',
    ];

    // RELATIONSHIPS
    //zao liko kwenye shamba moja
    public function farm() {
        return $this->belongsTo(Farm::class);
    }

    //zao lina shughuli nyingi
    public function activities() {
        return $this->hasMany(Crop_activity::class);
    }

    // Helper methods
    // count days remaining to harvest
    public function daysToHarvest() {
        if (!$this->harvest_date) return null;

        return now()->diffInDays($this->harvest_date, false);
    }

    // je zao liko active?
    public function isActive() {
        return in_array($this->status, ['planted', 'growing']);
    }
}
