<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\RetrieveStockJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class Retrieve extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $name = 'Retrieve from source';

    public function __construct()
    {
        $this->onQueue(config('magento-stock.queue'));
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            RetrieveStockJob::dispatch($model->sku, $fields->force ?? false);
        }

        return Action::message(__('Retrieving :count stocks', ['count' => $models->count()]));
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Boolean::make('Force')
                ->help('Forces an update in Magento. Even if the stock hasn\'t changed')
        ];
    }
}
