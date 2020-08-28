<?php

namespace Restive\Parsers;

use Illuminate\Database\Eloquent\Builder;

class ParserWhereNotIn extends ParserWhereInAbstract
{
    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        $eloquentBuilder = $eloquentBuilder->whereNotIn($this->tokenized['col'], $this->tokenized['in']);
        return $eloquentBuilder;
    }
}
