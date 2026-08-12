<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRundown extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'time', 'description', 'order'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
