<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Loan extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'book_id',
        'member_id',
        'loan_date',
        'return_date',
        'status',
        'quantity',
    ];

    protected static function booted()
    {
        static::creating(function ($loan) {
            // Mengurangi stok buku saat pinjaman dibuat
            $book = Book::find($loan->book_id);
            if ($book && $book->stock >= $loan->quantity) {
                $book->stock -= $loan->quantity;
                $book->save();
            } else {
                throw ValidationException::withMessages([
                    'quantity' => 'Not enough stock available for this loan.',

                    Notification::make()
                        ->title('Buku Tidak Tersedia')
                        ->body('Stok buku tidak mencukupi untuk peminjaman ini.')
                        ->danger()
                        ->send(),
                ]);
            }
        });

        static::updating(function ($loan) {
            // Mengembalikan stok buku saat status pinjaman diubah ke "returned"
            if ($loan->isDirty('status') && $loan->status == 'returned') {
                $originalLoan = Loan::find($loan->id);
                $book = Book::find($loan->book_id);
                if ($book) {
                    // Menambah stok kembali ketika buku dikembalikan
                    $book->stock += $originalLoan->quantity;
                    $book->save();
                }
            }
        });

        static::deleted(function ($loan) {
            // Mengembalikan stok buku saat pinjaman dihapus
            $book = Book::find($loan->book_id);
            if ($book) {
                $book->stock += $loan->quantity;
                $book->save();
            }
        });
    }

    // Relasi dengan tabel books
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relasi dengan tabel members
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
