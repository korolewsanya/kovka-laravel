<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Material;
use App\Models\WorkReport;
use App\Policies\ProductPolicy;
use App\Policies\OrderPolicy;
use App\Policies\MaterialPolicy;
use App\Policies\WorkReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    //Связывание моделей с их политиками (правилами доступа)
    //«Для модели Material использем правила из MaterialPolicy» и т.д.
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
        Material::class => MaterialPolicy::class,
        WorkReport::class => WorkReportPolicy::class,
    ];

    //выполняется при запуске приложения
    public function boot(): void
    {
    //Регистрируем все политики, перечисленные в $policies
        $this->registerPolicies();
    }
}