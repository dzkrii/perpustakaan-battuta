<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    // Relasi dengan tabel loans
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
