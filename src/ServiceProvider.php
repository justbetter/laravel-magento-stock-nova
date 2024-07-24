<?php

namespace JustBetter\MagentoStockNova;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JustBetter\MagentoStockNova\Nova\StockResource;
use Laravel\Nova\Nova;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        Nova::serving(function (): void {
            Nova::resources([
                StockResource::class,
            ]);
        });
    }
}
