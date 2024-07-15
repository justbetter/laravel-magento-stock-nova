<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\Update\UpdateStockJob;
use JustBetter\MagentoStock\Models\Stock;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class UpdateAll extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this
            ->withName(__('Update all to Magento'))
            ->standalone()
            ->onQueue(config('magento-stock.queue'));
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $filter = $fields->get('filter');

        Stock::query()
            ->when($filter === 'in_stock', fn (Builder $query) => $query->where('in_stock', true))
            ->when($filter === 'out_stock', fn (Builder $query) => $query->where('in_stock', false))
            ->get()
            ->each(fn (Stock $stock) => UpdateStockJob::dispatch($stock));

        return ActionResponse::message(__('Updating...'));
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Select::make(__('Filter'), 'filter')
                ->rules(['required'])
                ->options([
                    'all' => __('All stock'),
                    'in_stock' => __('Only all in stock'),
                    'out_stock' => __('Only all out of stock'),
                ]),
        ];
    }
}
