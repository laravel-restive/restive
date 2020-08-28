<?php

namespace Restive\Parsers;

use Illuminate\Database\Eloquent\Builder;

interface ParserInterface
{
    public function tokenizeParameters(string $parameters);
    public function prepareQuery(Builder $eloquentBuilder): Builder;
}
