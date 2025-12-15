<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Http\Contracts\Auth\RegisterRepositoryInterface;
use App\Http\Contracts\Auth\LoginRepositoryInterface;
use App\Http\Contracts\Apps\SupplierRepositoryInterface;
use App\Http\Contracts\Apps\CategoryRepositoryInterface;
use App\Http\Contracts\Apps\LocationRepositoryInteface;
use App\Http\Contracts\Apps\LocationTypeRepositoryInteface;
use App\Http\Contracts\Apps\ProductRepositoryInterface;
use App\Http\Contracts\Apps\UoMRepositoryInterface;
use App\Http\Contracts\Apps\WarehouseRepositoryInterface;
// Repository
use App\Http\Repositories\Auth\RegisterRepository;
use App\Http\Repositories\Apps\SupplierRepository;
use App\Http\Repositories\Apps\CategoryRepository;
use App\Http\Repositories\Apps\LocationRepository;
use App\Http\Repositories\Apps\LocationTypeRepository;
use App\Http\Repositories\Apps\ProductRepository;
use App\Http\Repositories\Apps\UoMRepository;
use App\Http\Repositories\Apps\WarehouseRepository;
use App\Http\Repositories\Auth\LoginRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Apps
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(UoMRepositoryInterface::class, UoMRepository::class);
        $this->app->bind(WarehouseRepositoryInterface::class, WarehouseRepository::class);
        $this->app->bind(LocationTypeRepositoryInteface::class, LocationTypeRepository::class);
        $this->app->bind(LocationRepositoryInteface::class, LocationRepository::class);

        // Auth
        $this->app->bind(RegisterRepositoryInterface::class, RegisterRepository::class);
        $this->app->bind(LoginRepositoryInterface::class, LoginRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
