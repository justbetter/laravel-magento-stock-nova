<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Jobs\RetrieveAllStockJob;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class RetrieveAll extends Action
{
    public $name = 'Retrieve all from source';

    public $standalone = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        RetrieveAllStockJob::dispatch();

        return Action::message(__('Retrieving'));
    }
}
