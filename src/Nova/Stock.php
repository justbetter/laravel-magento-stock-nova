<?php

namespace JustBetter\MagentoStockNova\Nova;

use Bolechen\NovaActivitylog\Resources\Activitylog;
use Illuminate\Http\Request;
use JustBetter\NovaErrorLogger\Nova\Error;
use JustBetter\MagentoStock\Models\MagentoStock;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Stock extends Resource
{
    public static $model = MagentoStock::class;

    public static $title = 'sku';

    public static $group = 'stock';

    public static $search = [
        'sku'
    ];

    public static function label(): string
    {
        return 'Stock';
    }

    public function fields(NovaRequest $request): array
    {
        $fields = [

            Boolean::make(__('Keep in sync'), 'sync')
                ->help(__('Disable if this product stock should not be synced'))
                ->sortable(),

            Text::make(__('SKU'), 'sku')
                ->readonly()
                ->sortable(),
        ];

        if (config('magento-stock.msi')) {
            $fields[] = Text::make('MSI', function () {
                $output = '';

                $msiStatusses = $this->model()->msi_status;

                foreach ($this->model()->msi_stock ?? [] as $source => $qty) {

                    $status = ($msiStatusses[$source] ?? false)
                        ? 'In Stock'
                        : 'Out of Stock';

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

        if (!config('magento-stock-nova.hide.single_retrieve')) {
            $fields[] = Boolean::make(__('Retrieve'), 'retrieve')
                ->help(__('Automatically set to true if this product should be retrieved'))
                ->sortable();
        }

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
                ->help('Max allowed failures: '.config('magento-stock.fails.count'))
                ->sortable(),

            Number::make(__('Fail count'), 'fail_count')
                ->readonly()
                ->onlyOnDetail(),

            MorphMany::make(__('Activity'), 'activities', Activitylog::class),

            MorphMany::make(__('Errors'), 'errors', Error::class),
        ]);
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    public function actions(NovaRequest $request): array
    {
        $actions = [
            Actions\Upload::make(),
            Actions\UploadAll::make(),

            Actions\ResetFailures::make(),
            Actions\Sync::make(),
            Actions\Retry::make(),

            Actions\Compare::make()
        ];

        if (!config('magento-stock-nova.hide.single_retrieve')) {
            $actions[] = Actions\Retrieve::make();
        }

        if (!config('magento-stock-nova.hide.retrieve_all')) {
            $actions[] = Actions\RetrieveAll::make();
        }

        return $actions;
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
}
