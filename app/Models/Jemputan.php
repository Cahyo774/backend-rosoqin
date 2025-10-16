<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jemputan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alamat',
        'latitude',
        'longitude',
        'status',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}
