<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'label',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get formatted time display
     */
    public function getFormattedTimeAttribute()
    {
        $start = $this->start_time;
        if (is_string($start)) {
            $start = date('g:i A', strtotime($start));
        } else {
            $start = $start->format('g:i A');
        }
        
        if ($this->end_time) {
            $end = $this->end_time;
            if (is_string($end)) {
                $end = date('g:i A', strtotime($end));
            } else {
                $end = $end->format('g:i A');
            }
            return $start . ' - ' . $end;
        }
        return $start;
    }

    /**
     * Get time value for form input (HH:MM format)
     */
    public function getTimeValueAttribute()
    {
        $time = $this->start_time;
        if (is_string($time)) {
            return date('H:i', strtotime($time));
        }
        return $time->format('H:i');
    }
}
