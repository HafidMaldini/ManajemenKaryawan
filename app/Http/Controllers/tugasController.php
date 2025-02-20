<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class tugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['user.role', 'user.team'])->get();
        // dd($tugas2);
        $user = Auth::user();
        
        return view('index.task', compact('tugas', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:50',
            'priority' => 'required|string|max:10',
        ]);

        $id = Auth::user()->id;
        $tugas = Tugas::create([
            'judul' => $request->judul,
            'user_id' => $id,
            'status' => 'Assigned',
            'priority' => $request->priority,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat.',
            'data' => $tugas
        ], 201); // 201 adalah status code untuk "Created"
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:20',
            'deskripsi' => 'nullable|string',
        ]);

        $task = Tugas::findOrFail($id);
        $task->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui.',
            'data' => $task
        ]);
    }

    public function destroy($id)
    {
        $tugas = Tugas::find($id);

        if (!$tugas) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan.'
            ], 404); // 404 adalah status code untuk "Not Found"
        }

        $tugas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus.'
        ]);
    }

    public function start($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->update([
            'status' => 'On Progress',
            'start_date' => now('WIB'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dimulai.',
            'data' => $tugas
        ]);
    }

    public function finish($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->update([
            'status' => 'Submited',
            'end_date' => now('WIB'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diselesaikan.',
            'data' => $tugas
        ]);
    }

    public function approve($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->update(['status' => 'Approved']);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil disetujui.',
            'data' => $tugas
        ]);
    }

    public function rejected($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->update([
            'status' => 'Revised',
            'end_date' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil ditolak.',
            'data' => $tugas
        ]);
    }

    public function Hold($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->update(['status' => 'On Hold']);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihold.',
            'data' => $tugas
        ]);
    }

    public function resume($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->update(['status' => 'On Progress']);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dilanjutkan.',
            'data' => $tugas
        ]);
    }
}
