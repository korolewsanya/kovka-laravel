<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

//Наследуем от ManageRecords (Список + модальные окна для create/edit/delete)
class ManageEmployees extends ManageRecords
{
    //Связь с EmployeeResource
    //Filament по этому свойству понимает, какой Resource обслуживает данная страница.
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
