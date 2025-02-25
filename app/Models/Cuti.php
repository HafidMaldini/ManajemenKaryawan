<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Cuti extends Model
{
    use Prunable;
    // public function CutiDecrement(){
    //     $this->decrement('sisa_cuti');
    // }
    protected $table = 'cuti';

    protected $fillable = [
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'reason',
        'status',
        'id_manager',
    ];

    protected $dates = ['tanggal_mulai', 'tanggal_selesai', 'deleted_at'];

    /**
     * Relasi ke tabel Users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'id_manager');
    }

    /**
     * Scope untuk mengambil hanya cuti yang disetujui
     */
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'Disetujui');
    }

    /**
     * Scope untuk mengambil hanya cuti yang menunggu persetujuan
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'Menunggu');
    }

    /**
     * Scope untuk mengambil hanya cuti yang ditolak
     */
    public function scopeDitolak($query)
    {
        return $query->where('status', 'Ditolak');
    }
}
