<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'description',
        'year_published',
        'stock',
    ];

    // Relasi dengan tabel loans
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
