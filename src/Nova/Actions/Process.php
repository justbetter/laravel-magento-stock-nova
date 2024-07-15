<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\ProcessStocksJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;

class Process extends Action
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this
            ->withName(__('Process pending stock retrievals and updates'))
            ->standalone();
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        ProcessStocksJob::dispatch();

        return ActionResponse::message(__('Processing...'));
    }
}
