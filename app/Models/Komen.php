<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'sentiment',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id');
    }
}
