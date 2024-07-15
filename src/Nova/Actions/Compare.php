<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\Comparison\DispatchComparisonsJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;

class Compare extends Action
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this
            ->withName(__('Compare Stock between Nova and Magento'))
            ->standalone();
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        DispatchComparisonsJob::dispatch();

        return ActionResponse::message(__('Comparing...'));
    }
}
