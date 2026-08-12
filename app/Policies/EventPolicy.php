<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Pimpinan (kahim, wakahim) memiliki akses penuh.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isPimpinan()) {
            return true;
        }
        return null;
    }

    /**
     * Semua user terautentikasi bisa melihat kegiatan.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Kadiv bisa membuat kegiatan untuk divisinya sendiri.
     */
    public function create(User $user): bool
    {
        return $user->canCreateEvent();
    }

    /**
     * Kadiv (divisi sama) dan pimpinan bisa mengubah kegiatan.
     */
    public function update(User $user, Event $event): bool
    {
        if ($user->isPimpinan()) {
            return true;
        }
        // Kadiv hanya untuk divisinya sendiri (division = string name)
        return $user->isKadiv() && $user->divisi === $event->division;
    }

    /**
     * Hanya pimpinan yang bisa membatalkan kegiatan.
     */
    public function cancel(User $user, Event $event): bool
    {
        return $user->isPimpinan();
    }

    /**
     * Hanya pimpinan yang bisa menghapus kegiatan.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->canDeleteEvent();
    }
}
