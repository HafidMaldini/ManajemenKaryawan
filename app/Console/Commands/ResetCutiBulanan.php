<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetCutiBulanan extends Command
{
    // Command signature dan deskripsi
    protected $signature = 'cuti:reset';
    protected $description = 'Reset sisa cuti semua pengguna menjadi 3 setiap awal bulan';

    public function handle()
    {
        try {
            // Reset cuti dengan query massal
            $affected = User::query()->update(['sisa_cuti' => 3]);

            // Beri info di terminal
            $this->info("✅ Sisa cuti berhasil direset menjadi 3 untuk $affected pengguna.");

            // Catat di log
            Log::info("Sisa cuti berhasil direset menjadi 3 untuk $affected pengguna.");

        } catch (\Exception $e) {
            // Tangani error jika terjadi
            $this->error('❌ Gagal mereset sisa cuti: ' . $e->getMessage());
            Log::error('Gagal mereset sisa cuti', ['error' => $e->getMessage()]);
        }
    }
}
