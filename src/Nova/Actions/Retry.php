<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Models\MagentoStock;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class Retry extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = 'Retry Stock';

    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each(fn(MagentoStock $stock) => $stock->update(['sync' => true, 'update' => true, 'fail_count' => 0]));
    }
}
