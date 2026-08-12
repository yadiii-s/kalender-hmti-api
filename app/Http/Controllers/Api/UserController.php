<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // ─── Index (Pimpinan only) ────────────────────────────────────

    public function index(Request $request)
    {
        if (!auth()->user()->canManageUsers()) {
            return response()->json(['message' => 'Tidak memiliki izin untuk melihat daftar pengguna'], 403);
        }

        $query = User::query();

        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nim', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        return response()->json($query->orderBy('name')->get());
    }

    // ─── Show ─────────────────────────────────────────────────────

    public function show($id)
    {
        // User bisa lihat profil sendiri, admin bisa lihat semua
        if (auth()->id() != $id && !auth()->user()->canManageUsers()) {
            return response()->json(['message' => 'Tidak memiliki izin'], 403);
        }

        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // ─── Update ───────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $authUser = auth()->user();

        // Hanya pimpinan yang bisa update user lain
        if ($authUser->id != $id && !$authUser->canManageUsers()) {
            return response()->json(['message' => 'Tidak memiliki izin untuk mengubah data pengguna lain'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'       => 'sometimes|string|max:255',
            'email'      => 'sometimes|string|email|unique:users,email,' . $id,
            'phone'      => 'nullable|string|max:20',
            'angkatan'   => 'nullable|string|max:10',
            'jabatan'    => 'sometimes|in:kahim,wakahim,sekum1,sekum2,bendum1,bendum2,kadiv,sekdiv,bendiv,anggota',
            'divisi'     => 'nullable|string|in:KWSB,Internal,Eksternal,Minbak,Sosma,Infokom,KWU',
            'sub_divisi' => 'nullable|string|max:100',
            'status'     => 'sometimes|in:aktif,nonaktif',
            'password'   => 'sometimes|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'email', 'phone', 'angkatan', 'jabatan', 'divisi', 'sub_divisi', 'status']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Data pengguna berhasil diperbarui',
            'data'    => $user,
        ]);
    }

    // ─── Destroy (Pimpinan only) ──────────────────────────────────

    public function destroy($id)
    {
        if (!auth()->user()->canManageUsers()) {
            return response()->json(['message' => 'Tidak memiliki izin untuk menghapus pengguna'], 403);
        }

        // Tidak boleh hapus diri sendiri
        if (auth()->id() == $id) {
            return response()->json(['message' => 'Tidak dapat menghapus akun sendiri'], 400);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Pengguna berhasil dihapus']);
    }

    // ─── Dashboard Stats ──────────────────────────────────────────

    public function dashboardStats()
    {
        $divisions = ['KWSB', 'Internal', 'Eksternal', 'Minbak', 'Sosma', 'Infokom', 'KWU'];

        $divisionStats = [];
        foreach ($divisions as $div) {
            $divisionStats[$div] = Event::where('division', $div)->count();
        }

        $recentEvents = Event::with(['rundowns', 'documents'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($e) => [
                'id'       => $e->id,
                'event_id' => $e->event_id,
                'title'    => $e->title,
                'division' => $e->division,
                'status'   => $e->status,
                'start'    => $e->start_time?->toISOString(),
            ]);

        return response()->json([
            'total_users'      => User::count(),
            'active_users'     => User::where('status', 'aktif')->count(),
            'total_events'     => Event::count(),
            'upcoming_events'  => Event::where('status', 'Mendatang')->count(),
            'ongoing_events'   => Event::where('status', 'Berlangsung')->count(),
            'completed_events' => Event::where('status', 'Selesai')->count(),
            'division_stats'   => $divisionStats,
            'recent_events'    => $recentEvents,
        ]);
    }
}
