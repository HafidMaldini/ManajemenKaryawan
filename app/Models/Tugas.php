<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Tugas extends Model
{
    use Prunable;
    protected $fillable = [
        'manager_id', 'karyawan_id', 'title',
        'deadline', 'completed_at', 'notes', 'status'
    ];

    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function karyawan() {
        return $this->belongsTo(User::class, 'karyawan_id');
    }
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

}
