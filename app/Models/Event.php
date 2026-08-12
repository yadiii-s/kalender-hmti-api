<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'division',
        'pic',
        'start_time',
        'end_time',
        'location',
        'status',
        'description',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    // ─── Boot: Auto-generate event_id ─────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->event_id)) {
                $latest = self::orderBy('id', 'desc')->first();
                $number = $latest ? (intval(substr($latest->event_id, 1)) + 1) : 1;
                $event->event_id = 'E' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // ─── Relations ───────────────────────────────────────────────

    public function rundowns()
    {
        return $this->hasMany(EventRundown::class)->orderBy('order');
    }

    public function documents()
    {
        return $this->hasMany(EventDocument::class);
    }
}
