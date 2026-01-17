<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait Sortable
{
    /**
     * Sort query
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return Builder
     */
    public function scopeSorted(Builder $builder): Builder
    {
        $model = $builder->getModel();
        $table = $model->getTable();
        $sortBy = request('sort_by') && Schema::hasColumn($table, request('sort_by')) ? request('sort_by') : 'id';
        $sort = in_array(request('sort'), ['asc', 'desc']) ? request('sort') : 'asc';
        return $builder->when(
            $sortBy && $sort && request()->is('api/*'),
            fn($q) => $q->orderBy($sortBy, $sort)
        );
    }
}