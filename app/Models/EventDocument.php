<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDocument extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'icon', 'file_path'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
