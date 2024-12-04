<?php

namespace App\Filament\Resources\LoanReturnedResource\Pages;

use App\Filament\Resources\LoanReturnedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoanReturneds extends ListRecords
{
    protected static string $resource = LoanReturnedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
