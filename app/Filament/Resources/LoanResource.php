<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Peminjaman Buku';

    protected static ?string $navigationGroup = 'Peminjaman';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('member_id')
                    ->label('Member')
                    ->options(Member::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->disabled(fn($state, $component) => $component->getRecord() !== null),
                Forms\Components\Select::make('book_id')
                    ->label('Buku yang Dipinjam')
                    ->options(Book::all()->pluck('title', 'id'))
                    ->searchable()
                    ->required()
                    ->disabled(fn($state, $component) => $component->getRecord() !== null),
                Forms\Components\DatePicker::make('loan_date')
                    ->label('Tanggal Peminjaman')
                    ->required()
                    ->disabled(fn($state, $component) => $component->getRecord() !== null),
                Forms\Components\DatePicker::make('return_date')
                    ->label('Tanggal Pengembalian')
                    ->required(fn($state, $component) => $component->getRecord() !== null)
                    ->disabled(fn($state, $component) => $component->getRecord() === null),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->disabled(fn($state, $component) => $component->getRecord() !== null),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'borrowed' => 'Borrowed',
                        'returned' => 'Returned',
                    ])
                    ->default('borrowed')
                    ->required()
                    ->disabled(fn($state, $component) => $component->getRecord() === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.name')
                    ->label('Member')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('book.title')
                    ->label('Buku yang Dipinjam')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('loan_date')
                    ->label('Tanggal Peminjaman')
                    ->date(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'borrowed' => 'warning',
                        'returned' => 'success',
                    }),
            ])
            ->filters([
                // 
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Filter data hanya untuk status 'returned'
        return parent::getEloquentQuery()
            ->where('status', 'borrowed');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
