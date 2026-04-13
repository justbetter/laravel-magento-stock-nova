<?php

declare(strict_types=1);

namespace JustBetter\MagentoStockNova\Nova\Metrics;

use JustBetter\MagentoStock\Models\Stock;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;

class StocksToUpdate extends Value
{
    public $name = 'Stock To Update';

    public function calculate(NovaRequest $request): ValueResult
    {
        return new ValueResult(
            Stock::query()
                ->where('update', '=', true)
                ->count()
        );
    }

    #[\Override]
    public function uriKey(): string
    {
        return 'stocks-to-update';
    }
}
