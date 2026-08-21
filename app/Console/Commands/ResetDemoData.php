<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetDemoData extends Command
{
    protected $signature = 'demo:reset';
    protected $description = 'Сброс демо-данных к исходному состоянию';

    public function handle()
    {
        $this->info('🔄 Начинаю сброс демо-данных...');
        
        Artisan::call('db:seed', ['--class' => 'DemoDataSeeder', '--force' => true]);
        
        $this->info('✅ Демо-данные успешно сброшены!');
    }
}