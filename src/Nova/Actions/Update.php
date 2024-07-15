<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\Update\UpdateStockJob;
use JustBetter\MagentoStock\Models\Stock;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;

class Update extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this
            ->withName(__('Update to Magento'))
            ->onQueue(config('magento-stock.queue'));
    }

    /** @param Collection<int, Stock> $models */
    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        foreach ($models as $model) {
            UpdateStockJob::dispatch($model);
        }

        return ActionResponse::message(__('Updating...'));
    }
}
