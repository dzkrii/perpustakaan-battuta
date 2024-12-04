<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use App\Imports\ImportBooks;
use App\Models\Book;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        $data = Actions\CreateAction::make();
        return view('filament.custom.upload-file', compact('data'));
    }

    public $file = '';

    public function save()
    {
        if ($this->file != '') {
            Excel::import(new ImportBooks, $this->file);
        }


        // Book::create([
        //     'title' => 'Judul Buku',
        //     'author' => 'Penulis',
        //     'publisher' => 'Penerbit',
        //     'description' => 'Deskripsi',
        //     'year_published' => 'Tahun Terbit',
        //     'stock' => 1,
        // ]);
    }
}
