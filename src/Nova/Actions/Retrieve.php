<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\Retrieval\RetrieveStockJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class Retrieve extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this
            ->withName(__('Retrieve from source'))
            ->onQueue(config('magento-stock.queue'));
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        foreach ($models as $model) {
            RetrieveStockJob::dispatch($model->sku, $fields->get('force', false));
        }

        return ActionResponse::message(__('Retrieving :count stocks...', ['count' => $models->count()]));
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Boolean::make(__('Force'), 'force')
                ->help(__('Forces an update in Magento, even if the stock hasn\'t changed.')),
        ];
    }
}
