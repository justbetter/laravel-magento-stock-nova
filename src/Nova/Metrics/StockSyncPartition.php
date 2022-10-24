<?php

namespace JustBetter\MagentoStockNova\Nova\Metrics;

use JustBetter\MagentoStock\Models\MagentoStock;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class StockSyncPartition extends Partition
{
    public $name = 'Stock Keep in Sync';

    public function calculate(NovaRequest $request): PartitionResult
    {
        return $this->count($request, MagentoStock::class, 'sync')
            ->label(fn($sync) => $sync ? 'Yes' : 'No');
    }

    public function uriKey(): string
    {
        return 'stock-sync-partition';
    }
}
