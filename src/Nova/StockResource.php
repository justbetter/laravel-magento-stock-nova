<?php

namespace JustBetter\MagentoStockNova\Nova;

use Bolechen\NovaActivitylog\Resources\Activitylog;
use Illuminate\Http\Request;
use JustBetter\MagentoStock\Models\Stock;
use JustBetter\MagentoStock\Repositories\BaseRepository;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class StockResource extends Resource
{
    public static string $model = Stock::class;

    public static $title = 'sku';

    public static $group = 'stock';

    public static $search = [
        'sku',
    ];

    public static function label(): string
    {
        return __('Stock');
    }

    public static function uriKey(): string
    {
        return 'magento-stock';
    }

    public function fields(NovaRequest $request): array
    {
        $repository = BaseRepository::resolve();

        /** @var Stock $model */
        $model = $this->model();

        $fields = [
            Boolean::make(__('Keep in sync'), 'sync')
                ->help(__('Disable if this product stock should not be synced'))
                ->sortable(),

            Text::make(__('SKU'), 'sku')
                ->readonly()
                ->sortable(),
        ];

        if ($repository->msi()) {
            $fields[] = Text::make(__('MSI'), function () use ($model) {
                $output = '';

                $msiStatusses = $model->msi_status;

                foreach ($model->msi_stock ?? [] as $source => $qty) {
                    $status = ($msiStatusses[$source] ?? false)
                        ? __('In Stock')
                        : __('Out of Stock');

                    $output .= "<b>$source:</b> $status - $qty<br/>";
                }

                return $output;
            })->asHtml();

            $fields[] = KeyValue::make(__('MSI Quantities'), 'msi_stock');

            $fields[] = KeyValue::make(__('MSI Status'), 'msi_status');

        } else {
            $fields[] = Boolean::make(__('In Stock'), 'in_stock')
                ->readonly()
                ->sortable();

            $fields[] = Number::make(__('Quantity'), 'quantity')
                ->readonly()
                ->sortable();
        }

        $fields[] = Boolean::make(__('Backorders Allowed'), 'backorders')
            ->readonly()
            ->sortable();

        $fields[] = Boolean::make(__('Retrieve'), 'retrieve')
            ->help(__('Automatically set to true if this product should be retrieved'))
            ->sortable();

        return array_merge($fields, [
            Boolean::make(__('Update'), 'update')
                ->help(__('Automatically set to true if this product should be updated in Magento'))
                ->sortable(),

            DateTime::make(__('Last retrieved'), 'last_retrieved')
                ->readonly()
                ->sortable(),

            DateTime::make(__('Last updated'), 'last_updated')
                ->readonly()
                ->sortable(),

            DateTime::make(__('Last failed'), 'last_failed')
                ->readonly()
                ->help(__('Max allowed failures: :limit', ['limit' => $repository->failLimit()]))
                ->sortable(),

            Number::make(__('Fail count'), 'fail_count')
                ->readonly()
                ->onlyOnDetail(),

            MorphMany::make(__('Activity'), 'activities', Activitylog::class),
        ]);
    }

    public function actions(NovaRequest $request): array
    {
        return [
            Actions\Retrieve::make(),
            Actions\RetrieveAll::make(),

            Actions\Update::make(),
            Actions\UpdateAll::make(),

            Actions\ResetFailures::make(),
            Actions\Process::make(),
            Actions\Retry::make(),

            Actions\Compare::make(),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            Filters\Status::make(),
            Filters\Sync::make(),
            Filters\StockStatus::make(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [
            Metrics\StocksToRetrieve::make(),
            Metrics\StocksToUpdate::make(),
            Metrics\StockSyncPartition::make(),
            Metrics\StockRetrievalsPerDay::make(),
            Metrics\StockUpdatesPerDay::make(),
            Metrics\StockErrorsPerDay::make(),
        ];
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }
}
