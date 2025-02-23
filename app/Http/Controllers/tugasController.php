<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\User;
use App\Notifications\TugasStatusNotification;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class tugasController extends Controller
{
    public function index()
    {
        $tugas = Auth::user()->role->name == 'Manager'
    ? Tugas::with(['karyawan.role', 'karyawan.team'])
        ->where('manager_id', Auth::id())
        ->get()
    : Tugas::with(['karyawan.role', 'karyawan.team'])
        ->where('karyawan_id', Auth::id())
        ->get();

        $user = Auth::user();
        $users = User::where('role_id', 1)->where('team_id', $user->team_id)->get();
        return view('index.task', compact('tugas', 'user', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'karyawan_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
        ]);


        $id = Auth::user()->id;
        $tugas = Tugas::create([
            'manager_id' => $id,
            'karyawan_id' => $request->karyawan_id,
            'title' => $request->title,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
            'status' => 'Assigned',
        ]);

        $tugas->karyawan->notify(new TugasStatusNotification($tugas, 'ditugaskan', Auth::user()));

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat.',
            'data' => $tugas
        ], 201); // 201 adalah status code untuk "Created"
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'karyawan_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
        ]);

        $task = Tugas::findOrFail($id);
        $task->update([
            'karyawan_id' => $request->karyawan_id,
            'title' => $request->title,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
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
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dimulai.',
            'data' => $tugas
        ]);
    }

    public function finish(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        $request->validate([
            'notes' => 'required|string|max:100',
        ]);

        $tugas->update([
            'notes' => $request->notes,
            'status' => 'Submited',
            'completed_at' => now('WIB'),
        ]);

        $tugas->manager->notify(new TugasStatusNotification($tugas, 'diselesaikan', Auth::user()));

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

        $tugas->karyawan->notify(new TugasStatusNotification($tugas, 'disetujui', Auth::user()));

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

        $tugas->karyawan->notify(new TugasStatusNotification($tugas, 'ditolak', Auth::user()));

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
