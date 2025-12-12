<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function cities()
    {
        return $this->hasMany(City::class)->orderBy('sort_order')->orderBy('name');
    }

    public function activeCities()
    {
        return $this->hasMany(City::class)->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }
}
