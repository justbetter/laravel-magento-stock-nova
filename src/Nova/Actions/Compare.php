<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\DispatchComparisonsJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Compare extends Action
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $name = 'Compare Stock between Nova and Magento';

    public $standalone = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        DispatchComparisonsJob::dispatch();

        return Action::message(__('Starting compare'));
    }
}
