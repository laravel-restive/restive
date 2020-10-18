<?php declare(strict_types=1);

namespace Restive\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface RequestParser
{
    public function buildQuery(Builder $query) : Builder;
}