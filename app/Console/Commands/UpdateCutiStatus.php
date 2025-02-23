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
    protected $description = 'Memperbarui status kerja karyawan berdasarkan tanggal cuti yan disetujui';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $cutisMulai = Cuti::where('tanggal_mulai', $today)
                          ->where('status', 'Approved')
                          ->get();

        foreach ($cutisMulai as $cuti) {
            $user = $cuti->user;
            $user->update(['status_kerja' => 'Cuti']);
            $this->info("Status kerja {$user->name} diperbarui menjadi 'Cuti'.");
        }

        $cutisSelesai = Cuti::where('tanggal_selesai', '<', $today) 
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
    
