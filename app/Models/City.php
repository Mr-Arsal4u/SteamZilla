<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class)->orderBy('sort_order')->orderBy('name');
    }

    public function activePlaces()
    {
        return $this->hasMany(Place::class)->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }
}
