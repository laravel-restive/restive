<?php

namespace Restive\Parsers;

use Illuminate\Database\Eloquent\Builder;
use Restive\Exceptions\ApiException;

class ParserForce extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        $this->tokenized['forceDelete'] = $parameters ?? 'true';
    }

    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        try {
            if ($this->tokenized['forceDelete'] === 'true') {
                $eloquentBuilder->forceDelete();
            }
        } catch (\BadMethodCallException $e) {
            throw new ApiException('Model does not support soft deletes');
        }
        return $eloquentBuilder;
    }
}
