<?php

namespace App\Providers;

use App\Repositories\Contracts\ServiceOrderRepositoryInterface;
use App\Repositories\Contracts\SparePartRepositoryInterface;
use App\Repositories\Eloquent\ServiceOrderRepository;
use App\Repositories\Eloquent\SparePartRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        ServiceOrderRepositoryInterface::class => ServiceOrderRepository::class,
        SparePartRepositoryInterface::class => SparePartRepository::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
