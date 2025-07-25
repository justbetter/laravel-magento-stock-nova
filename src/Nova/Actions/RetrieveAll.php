<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\Retrieval\RetrieveAllStockJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class RetrieveAll extends Action
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this
            ->withName(__('Retrieve all from source'))
            ->standalone();
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        /** @var ?string $from */
        $from = $fields->get('from');

        if ($from !== null) {
            $carbon = Carbon::parse($from);
        }

        /** @var bool $defer */
        $defer = $fields->get('defer');

        RetrieveAllStockJob::dispatch($carbon ?? null, $defer);

        return ActionResponse::message(__('Retrieving...'));
    }

    public function fields(NovaRequest $request): array
    {
        return [
            DateTime::make(__('From'), 'from')
                ->help(__('Optional, retrieve updated prices from this date')),

            Boolean::make(__('Defer'), 'defer')
                ->default(true)
                ->help(__('When enabled, the stocks will be marked for retrieval. Otherwise, all stocks will be retrieved immediately.')),
        ];
    }
}
