<?php

namespace App\Filament\Resources\Accesses\Pages;

use App\Filament\Resources\Accesses\AccessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAccesses extends ManageRecords
{
    protected static string $resource = AccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
