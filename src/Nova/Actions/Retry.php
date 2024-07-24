<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Models\Stock;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;

class Retry extends Action
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this->withName(__('Retry stock'));
    }

    public function handle(ActionFields $fields, Collection $models): ActionResponse
    {
        $models->each(fn (Stock $stock) => $stock->update(['sync' => true, 'update' => true, 'fail_count' => 0]));

        return ActionResponse::message(__('Retrying...'));
    }
}
