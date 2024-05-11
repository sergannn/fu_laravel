<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'lat',
        'lon',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
