<?php

namespace JustBetter\MagentoStockNova\Nova\Metrics;

use JustBetter\MagentoStock\Models\Stock;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class StocksToRetrieve extends Value
{
    public $name = 'Stock To Retrieve';

    public function calculate(NovaRequest $request): ValueResult
    {
        return new ValueResult(
            Stock::query()
                ->where('retrieve', '=', true)
                ->count()
        );
    }

    public function uriKey(): string
    {
        return 'stock-to-retrieve';
    }
}
