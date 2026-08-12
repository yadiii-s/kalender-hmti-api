<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'title',
        'icon',
        'bg_color',
        'image_path',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
