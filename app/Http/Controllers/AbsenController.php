<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function absenMasuk(Request $request)
    {
        $user = Auth::user()->id;
        $tanggal = now()->format('Y-m-d');
        $jamMasuk = now()->format('H:i:s');

        // Cek apakah user sudah absen hari ini
        $absen = Absen::where('user_id', $user)->where('tanggal', $tanggal)->first();
        if ($absen) {
            return response()->json(['status' => 'error', 'message' => 'Anda sudah absen hari ini!']);
        }

       
        $isLate = "";
        if(Carbon::now() >= Carbon::parse('08:00:00')){
            $isLate = "Telat";
        }
        if(Carbon::now() <= Carbon::parse('08:00:00')){
            $isLate = "Tepat Waktu";
            dd($isLate);
        }

        // dd($isLate);
        // Simpan ke database
        Absen::create([
            'user_id' => $user,
            'tanggal' => $tanggal,
            'jam_masuk' => $jamMasuk,
            'status' => $isLate, // Kolom 'telat' akan menyimpan status keterlambatan
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $isLate == 'Telat' ? 'Anda berhasil absen, namun Anda telat!' : 'Berhasil absen!',
        ]);
    }

    public function checkoutPulang(Request $request)
    {
        $user = Auth::user()->id;
        $tanggal = now()->format('Y-m-d');

        // Ambil data absen hari ini
        $absen = Absen::where('user_id', $user)->where('tanggal', $tanggal)->first();
        if (!$absen) {
            return response()->json(['status' => 'error', 'message' => 'Anda belum absen masuk hari ini!']);
        }
        if($absen->jam_pulang != null){
            return response()->json(['status' => 'error', 'message' => 'Anda sudah checkout hari ini!']);
        }

        $absen->update([
            'jam_pulang' => now()->format('H:i:s'),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Berhasil checkout!']);
    }

    public function getAbsensiByDate($date)
    {
        $user_id = Auth::id();
        $absensi = Absen::where('user_id', $user_id)->where('tanggal', $date)->first();

        return response()->json($absensi);
    }

    public function logAbsensi()
    {
        $user = Auth::user();
        if ($user->team->name !== 'HRD') {
            abort(403, 'Anda tidak memiliki akses!');
        }

        $logs = Absen::with('user')->orderBy('tanggal', 'desc')->get();
        // dd($logs);
        return view('index.log-absensi', compact('logs'));
    }
}
