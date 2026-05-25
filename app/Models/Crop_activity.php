<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop_activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'activity_type',
        'description',
        'activity_date',
    ];

    protected $casts = [
        'activity_date' => 'date',
    ];

    //RELATIONSHIPS
    //shughuli inafanywa kwa zao moja
    public function crop() {
        return $this->belongsTo(Crop::class);
    }
}
