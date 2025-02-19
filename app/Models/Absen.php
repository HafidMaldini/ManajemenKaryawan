<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Absen extends Model
{
    use Prunable;
    protected $table = 'absen';
    protected $fillable = ['user_id', 'tanggal', 'jam_masuk', 'jam_pulang'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
