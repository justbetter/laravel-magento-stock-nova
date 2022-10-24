<?php

namespace JustBetter\MagentoStockNova;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JustBetter\MagentoStockNova\Nova\Stock;
use Laravel\Nova\Nova;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/magento-stock-nova.php', 'magento-stock-nova');
    }

    public function boot(): void
    {
        $this
            ->bootConfig()
            ->bootNova();
    }

    protected function bootNova(): static
    {
        Nova::resources([
            Stock::class,
        ]);

        return $this;
    }

    protected function bootConfig(): static
    {
        $this->publishes([
            __DIR__ . '/../config/magento-stock-nova.php' => config_path('magento-stock-nova.php'),
        ], 'config');

        return $this;
    }
}
