<?php

declare(strict_types=1);

namespace JustBetter\MagentoStockNova\Nova\Metrics;

use JustBetter\MagentoStock\Models\Stock;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class StockSyncPartition extends Partition
{
    public $name = 'Stock Keep in Sync';

    public function calculate(NovaRequest $request): PartitionResult
    {
        return $this
            ->count($request, Stock::class, 'sync')
            ->label(fn (mixed $sync): string => $sync !== '' && $sync !== '0' ? __('Yes') : __('No'));
    }

    #[\Override]
    public function uriKey(): string
    {
        return 'stock-sync-partition';
    }
}
