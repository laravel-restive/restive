<?php

namespace Restive\Parsers;

use Restive\Exceptions\ParserParameterCountException;

abstract class ParserWhereBetweenAbstract extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        $parameters = $this->handleSeparatedParameters($parameters, ':');
        if (count($parameters) !== 3) {
            throw ParserParameterCountException::withCounts('whereBetween', 3, count($parameters));
        }
        $this->tokenized = $parameters;
    }
}
