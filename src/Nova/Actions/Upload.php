<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\UpdateStockJob;
use JustBetter\MagentoStock\Models\MagentoStock;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Upload extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $name = 'Upload to Magento';

    public function __construct()
    {
        $this->onQueue(config('magento-stock.queue'));
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var MagentoStock $model */
        foreach ($models as $model) {
            UpdateStockJob::dispatch($model->sku);
        }
    }
}
