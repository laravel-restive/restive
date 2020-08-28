<?php

namespace Restive\Parsers;

use Illuminate\Database\Eloquent\Builder;
use Restive\Exceptions\ParserInvalidParameterException;
use Restive\Exceptions\ParserParameterCountException;

class ParserScope extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        $parameters = $this->handleSeparatedParameters($parameters);
        if (count($parameters) === 0) {
            throw new ParserParameterCountException("includes parser - missing parameters");
        }
        foreach ($parameters as $field) {
            $this->tokenized[] = ['scope' => $field];
        }
    }

    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        $scope = $this->tokenized[0]['scope'];
        $model = $eloquentBuilder->getModel();
        $scopeMethod = 'scope' . ucfirst(strtolower($scope));
        if (!method_exists($model, $scopeMethod)) {
            throw new ParserInvalidParameterException('Scope does not exist');
        }
        $eloquentBuilder = $eloquentBuilder->{$scope}();
        return $eloquentBuilder;
    }
}
