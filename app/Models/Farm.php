<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'location',
        'latitude',
        'longitude',
        'size_acres',
        'soil_type',
    ];

    // Cast - aina sahihi ya data
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'size_acres' => 'float',
    ];

    // RELATIONSHIPS
    //shamba moja ni la user mmoja
    public function user() {
        return $this->belongsTo(User::class);
    }

    //shamba lina mazao mengi
    public function crops() {
        return $this->hasMany(Crop::class);
    }

}
