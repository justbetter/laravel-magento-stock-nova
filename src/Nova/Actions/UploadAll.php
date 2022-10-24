<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\UpdateStockJob;
use JustBetter\MagentoStock\Models\MagentoStock;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class UploadAll extends Action implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $name = 'Upload All to Magento';

    public $standalone = true;

    public function __construct()
    {
        $this->onQueue(config('magento-stock.queue'));
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        MagentoStock::query()
            ->when($fields->only_in_stock, fn(Builder $query) => $query->where('in_stock', true))
            ->when($fields->only_out_of_stock, fn(Builder $query) => $query->where('in_stock', false))
            ->get()
            ->each(fn(MagentoStock $stock) => UpdateStockJob::dispatch($stock->sku));
    }

    public function fields(NovaRequest $request): array
    {
        return [
            Boolean::make(__('Only in stock'), 'only_in_stock'),
            Boolean::make(__('Only out of stock'), 'only_out_of_stock'),
        ];
    }

}
