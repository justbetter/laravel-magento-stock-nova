<?php

declare(strict_types=1);

namespace JustBetter\MagentoStockNova\Nova\Metrics;

use JustBetter\MagentoStock\Models\Stock;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendResult;

class StockRetrievalsPerDay extends Trend
{
    public function calculate(NovaRequest $request): TrendResult
    {
        return $this->countByDays($request, Stock::class, 'last_retrieved');
    }

    #[\Override]
    public function ranges(): array
    {
        return [
            30 => __(':days days', ['days' => 30]),
            60 => __(':days days', ['days' => 60]),
            90 => __(':days days', ['days' => 90]),
        ];
    }

    #[\Override]
    public function uriKey(): string
    {
        return 'stock-retrievals-per-day';
    }
}
