<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use App\Notifications\CutiStatusNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $cutis = $user->role->name == 'Manager'
            ? Cuti::with(['user.role', 'user.team', 'manager'])
            ->whereHas('user', function ($query) use ($user) {
                $query->where('team_id', $user->team_id); // Tim yang sama dengan manager
            })
            ->get()
            : Cuti::with(['user.role', 'user.team', 'manager'])
            ->where('user_id', $user->id) // Hanya cuti si karyawan
            ->get();

        return view('index.Cuti', compact('cutis'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        $tanggal_mulai = Carbon::parse($request->tanggal_mulai);
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai);
        $durasi = $tanggal_mulai->diffInDays($tanggal_selesai) + 1; // Termasuk hari pertama

        if ($user->sisa_cuti < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Sisa cuti tidak mencukupi!.'
            ], 400);
        }


        $cuti = Cuti::create([
            'user_id' => $user->id,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'durasi' => $durasi,
            'reason' => $request->reason,
            'status' => 'Pending'
        ]);

        User::where('id', $user->id)->decrement('sisa_cuti', 1);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan cuti berhasil dikirim!',
            'data' => $cuti
        ]);
    }

    public function approve($id)
    {
        $cuti = Cuti::findOrFail($id);
        $manager = Auth::user();

        if ($manager->role->name != 'Manager' || $manager->team_id != $cuti->user->team_id) {
            return response()->json(['message' => 'Anda tidak memiliki izin!'], 403);
        }

        $cuti->user->notify(new CutiStatusNotification($cuti, 'disetujui'));

        $cuti->update(['status' => 'Approved', 'id_manager' => $manager->id]);

        return response()->json([
            'success' => true,
            'message' => 'Cuti telah disetujui!',
            'data' => $cuti
        ]);
    }

    public function reject($id)
    {
        $user = Auth::user();
        $cuti = Cuti::findOrFail($id);
        $manager = Auth::user();

        if ($manager->role->name != 'Manager' || $manager->team_id != $cuti->user->team_id) {
            return response()->json(['message' => 'Anda tidak memiliki izin!'], 403);
        }

        $cuti->user->notify(new CutiStatusNotification($cuti, 'ditolak'));

        $cuti->update(['status' => 'Rejected']);
        User::where('id', $user->id)->increment('sisa_cuti', 1);

        return response()->json([
            'success' => true,
            'message' => 'Cuti telah ditolak!',
            'data' => $cuti
        ]);
    }
}
