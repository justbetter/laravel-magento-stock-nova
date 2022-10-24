<?php

namespace JustBetter\MagentoStockNova\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Sync extends Filter
{
    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where('sync', $value);
    }

    public function options(Request $request): array
    {
        return [
            'Yes' => true,
            'No' => false,
        ];
    }
}
