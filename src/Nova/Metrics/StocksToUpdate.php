<?php

namespace JustBetter\MagentoStockNova\Nova\Metrics;

use JustBetter\MagentoStock\Models\MagentoStock;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class StocksToUpdate extends Value
{
    public $name = 'Stock To Update';

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->sum($request, MagentoStock::class, 'update', 'last_updated');
    }

    public function uriKey(): string
    {
        return 'stocks-to-update';
    }
}
