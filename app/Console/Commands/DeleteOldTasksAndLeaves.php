<?php

namespace App\Console\Commands;

use App\Models\Cuti;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldTasksAndLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:delete-old-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus tugas yang sudah disetujui dalam 3 hari, cuti yang ditolak dalam 3 hari, dan cuti yang disetujui sehari setelah tanggal cuti selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // 1. Hapus tugas yang sudah disetujui dalam 3 hari
        $deletedTasks = Tugas::where('status', 'Approved')
                            ->where('updated_at', '<', $today->subDays(3))
                            ->delete();

        // 2. Hapus cuti yang sudah ditolak dalam 3 hari
        $deletedRejectedLeaves = Cuti::where('status', 'Rejected')
                                     ->where('updated_at', '<', $today->subDays(1))
                                     ->delete();

        // 3. Hapus cuti yang disetujui sehari setelah tanggal cuti selesai
        $deletedApprovedLeaves = Cuti::where('status', 'Approved')
                                     ->where('tanggal_selesai', '<', $today->subDays(1))
                                     ->delete();

        // Log hasil pembersihan
        $this->info("Tugas yang dihapus: {$deletedTasks}");
        $this->info("Cuti yang ditolak dihapus: {$deletedRejectedLeaves}");
        $this->info("Cuti yang sudah selesai dihapus: {$deletedApprovedLeaves}");

        $this->info('Pembersihan selesai.');
    }
    }

