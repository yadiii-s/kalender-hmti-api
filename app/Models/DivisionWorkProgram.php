<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionWorkProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'name',
        'date',
        'pic',
        'status',
        'progress',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
