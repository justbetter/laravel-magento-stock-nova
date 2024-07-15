<?php

namespace JustBetter\MagentoStockNova\Nova\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Status extends Filter
{
    public function apply(Request $request, $query, $value): Builder
    {
        return $query->where($value, true);
    }

    public function options(Request $request): array
    {
        return [
            (string) __('To retrieve') => 'retrieve',
            (string) __('To update') => 'update',
        ];
    }
}
