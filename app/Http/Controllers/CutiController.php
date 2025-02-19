<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function index()
    {
        $cutis = Cuti::with('user')->get();
        return view('index.Cuti', compact('cutis'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->sisa_cuti < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Sisa cuti tidak mencukupi!.'
            ], 400);
        }

        $cuti = Cuti::create([
            'user_id' => $user->id,
            'tanggal' => $request->tanggal,
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

        $cuti->update(['status' => 'Approved']);

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

        $cuti->update(['status' => 'Rejected']);
        User::where('id', $user->id)->increment('sisa_cuti', 1);

        return response()->json([
            'success' => true,
            'message' => 'Cuti telah ditolak!',
            'data' => $cuti
        ]);
    }
}
