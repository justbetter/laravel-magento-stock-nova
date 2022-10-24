<?php

namespace JustBetter\MagentoStockNova\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use JustBetter\MagentoStock\Models\MagentoStock;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ResetFailures extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $name = 'Reset failures';

    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var MagentoStock $model */
        foreach ($models as $model) {
            $model->update([
                'fail_count' => 0,
                'sync' => true
            ]);
        }
    }
}
