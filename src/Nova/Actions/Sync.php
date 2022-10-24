<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\SyncStockJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Sync extends Action
{
    public $name = 'Run retrieve and update jobs';

    public $standalone = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        SyncStockJob::dispatch();

        return Action::message(__('Dispatching retrieve and update jobs'));
    }
}
