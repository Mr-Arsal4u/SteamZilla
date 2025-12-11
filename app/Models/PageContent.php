<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'section',
        'key',
        'value',
        'type',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public static function getContent($page, $section, $key, $default = null)
    {
        $content = self::where('page', $page)
            ->where('section', $section)
            ->where('key', $key)
            ->first();
        
        return $content ? $content->value : $default;
    }

    public static function setContent($page, $section, $key, $value, $type = 'text')
    {
        return self::updateOrCreate(
            [
                'page' => $page,
                'section' => $section,
                'key' => $key,
            ],
            [
                'value' => $value,
                'type' => $type,
            ]
        );
    }
}
