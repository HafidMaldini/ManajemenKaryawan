<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'team_id',
        'role_id',
        'status_kerja'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tugas() {
        return $this->hasMany(Tugas::class);
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function team() {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function absen()
    {
        return $this->hasMany(Absen::class, 'user_id');
    }
    
    public function getStatusKerjaAttribute($value)
{
    $today = Carbon::today();
    $cuti = Cuti::where('user_id', $this->id)
                ->where('status', 'Approved')
                ->where('tanggal_mulai', '<=', $today) // Cuti sudah dimulai
                ->where('tanggal_selesai', '>=', $today) // Cuti belum selesai
                ->first();

    return $cuti ? 'Cuti' : $value;
}
}

