<?php

namespace App\Filament\Resources\Finances\Pages;

use App\Filament\Resources\Finances\FinanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFinances extends ManageRecords
{
    protected static string $resource = FinanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
