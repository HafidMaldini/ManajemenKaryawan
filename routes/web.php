<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\tugasController;
use App\Models\Absen;
use App\Models\tugas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    $user_id = Auth::id();
    // dd(Carbon::parse('08:00:00'));
    $currentTime = Carbon::now('Asia/Jakarta');
    $absensi = Absen::where('user_id', $user_id)->get();
    return view('index.dashboard', compact('absensi', 'currentTime'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/task', [tugasController::class, 'index'])->name('task');
    Route::post('/task-create', [tugasController::class, 'store'])->name('task.store');
    Route::put('/task-edit/{id}', [tugasController::class, 'update'])->name('task.update');
    Route::delete('/task-destroy/{id}', [tugasController::class, 'destroy'])->name('task.destroy');
    Route::post('/task-start/{id}', [tugasController::class, 'start'])->name('task.start');
    Route::post('/task-submit/{id}', [tugasController::class, 'finish'])->name('task.submit');
    Route::post('/task-approve/{id}', [tugasController::class, 'approve'])->name('task.approve');
    Route::post('/task-reject/{id}', [tugasController::class, 'rejected'])->name('task.reject');
    Route::post('/task-hold/{id}', [tugasController::class, 'Hold'])->name('task.hold');
    Route::post('/task-resume/{id}', [tugasController::class, 'resume'])->name('task.resume');
    Route::get('/tasks/data', [tugasController::class, 'getData'])->name('tasks.data');
    Route::get('/UserManagement', [RegisteredUserController::class, 'create'])->name('user');
    Route::post('/UserManagement-create', [RegisteredUserController::class, 'store'])->name('user.store');
    Route::put('/UserManagement-edit/{id}', [RegisteredUserController::class, 'update'])->name('user.update');
    Route::delete('/UserManagement-destroy/{id}', [RegisteredUserController::class, 'destroy'])->name('user.destroy');
    Route::middleware([\App\Http\Middleware\CheckWifiConnection::class])->group(function () {
        Route::post('/absen', [AbsenController::class, 'absenMasuk'])->name('absen');
        Route::post('/checkout', [AbsenController::class, 'checkoutPulang'])->name('checkout');
        Route::get('/absensi/{date}', [AbsenController::class, 'getAbsensiByDate']);
    });
    Route::get('/log-absensi', [AbsenController::class, 'logAbsensi'])->name('log.absensi');
    Route::get('/Pengajuan-Cuti', [CutiController::class, 'index'])->name('cuti');
Route::post('/cuti/store', [CutiController::class, 'store'])->name('cuti.store');
Route::post('/cuti/approve/{id}', [CutiController::class, 'approve'])->name('cuti.approve');
Route::post('/cuti/reject/{id}', [CutiController::class, 'reject'])->name('cuti.reject');
});


require __DIR__.'/auth.php';
