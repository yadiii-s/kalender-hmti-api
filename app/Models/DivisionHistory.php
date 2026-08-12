<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'name',
        'date',
        'status',
        'icon',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
