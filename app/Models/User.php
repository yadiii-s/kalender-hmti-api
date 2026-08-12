<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nim',
        'name',
        'email',
        'password',
        'phone',
        'angkatan',
        'jabatan',
        'divisi',
        'sub_divisi',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function divisionMembers()
    {
        return $this->hasMany(DivisionMember::class);
    }

    // ─── Role Helpers ─────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return in_array($this->jabatan, ['kahim', 'wakahim', 'sekum1', 'sekum2', 'bendum1', 'bendum2']);
    }

    public function isPimpinan(): bool
    {
        return in_array($this->jabatan, ['kahim', 'wakahim']);
    }

    public function isKadiv(): bool
    {
        return $this->jabatan === 'kadiv';
    }

    public function isAnggota(): bool
    {
        return $this->jabatan === 'anggota';
    }

    // ─── Permission Helpers ───────────────────────────────────────

    public function canManageEvent(): bool
    {
        return in_array($this->jabatan, ['kahim', 'wakahim', 'kadiv']);
    }

    public function canCreateEvent(): bool
    {
        return in_array($this->jabatan, ['kahim', 'wakahim', 'kadiv']);
    }

    public function canEditEvent(): bool
    {
        return in_array($this->jabatan, ['kahim', 'wakahim', 'kadiv']);
    }

    public function canDeleteEvent(): bool
    {
        return in_array($this->jabatan, ['kahim', 'wakahim']);
    }

    public function canManageUsers(): bool
    {
        return in_array($this->jabatan, ['kahim', 'wakahim']);
    }

    public function canManageDivisions(): bool
    {
        return in_array($this->jabatan, ['kahim', 'wakahim']);
    }
}
