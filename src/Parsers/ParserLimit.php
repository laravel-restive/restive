<?php

namespace Restive\Parsers;

use Illuminate\Database\Eloquent\Builder;
use Restive\Exceptions\ParserParameterCountException;

class ParserLimit extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        if ($parameters === '') {
            throw new ParserParameterCountException("limit parser - missing parameters");
        }
        $this->tokenized[] = ['field' => $parameters];
    }

    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        static $count = 0;
        if ($count != 0) {
            return $eloquentBuilder;
        }
        $field = $this->tokenized[0]['field'];
        $eloquentBuilder = $eloquentBuilder->limit((int)$field);
        $count++;
        return $eloquentBuilder;
    }
}
