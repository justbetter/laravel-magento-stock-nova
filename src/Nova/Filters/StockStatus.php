<?php

namespace JustBetter\MagentoStockNova\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class StockStatus extends Filter
{
    public function apply(Request $request, EloquentBuilder $query, mixed $value): Builder|EloquentBuilder
    {
        return $query->where('in_stock', $value);
    }

    public function options(Request $request): array
    {
        return [
            (string) __('In stock') => true,
            (string) __('Out of stock') => false,
        ];
    }
}
