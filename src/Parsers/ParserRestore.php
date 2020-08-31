<?php

namespace Restive\Parsers;

use Illuminate\Database\Eloquent\Builder;
use Restive\Exceptions\ApiException;

class ParserRestore extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        $this->tokenized['restore'] = '';
    }

    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        try {
            $eloquentBuilder->restore();
        } catch (\BadMethodCallException $e) {
            throw new ApiException('Model does not support soft deletes');
        }
        return $eloquentBuilder;
    }
}
