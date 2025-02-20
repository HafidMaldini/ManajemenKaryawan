<?php

namespace App\Console\Commands;

use App\Models\Cuti;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateCutiStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuti:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbarui status kerja karyawan berdasarkan tanggal cuti yang disetujui';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday(); // Kemarin

        // **1. SET STATUS KERJA MENJADI "CUTI" JIKA CUTI DIMULAI HARI INI**
        $cutisMulai = Cuti::where('tanggal', $today)
                          ->where('status', 'Approved')
                          ->get();

        foreach ($cutisMulai as $cuti) {
            $user = $cuti->user;
            $user->update(['status_kerja' => 'Cuti']);
            $this->info("Status kerja {$user->name} diperbarui menjadi 'Cuti'.");
        }

        // **2. KEMBALIKAN STATUS KERJA MENJADI "BEKERJA" JIKA CUTI KEMARIN**
        $cutisSelesai = Cuti::where('tanggal', $yesterday) // Cuti kemarin sudah selesai
                            ->where('status', 'Approved')
                            ->get();

        foreach ($cutisSelesai as $cuti) {
            $user = $cuti->user;
            $user->update(['status_kerja' => 'Bekerja']);
            $this->info("Status kerja {$user->name} dikembalikan menjadi 'Bekerja'.");
        }

        $this->info('Update status cuti selesai.');
    }
    }
