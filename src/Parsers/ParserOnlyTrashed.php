<?php
declare(strict_types = 1);

namespace Restive\Parsers;

use Illuminate\Database\Eloquent\Builder;
use Restive\Exceptions\ApiException;

class ParserOnlyTrashed extends ParserAbstract
{
    protected $validator = ['boolean'];

    public function buildQuery(Builder $query) : Builder
    {
        if ($this->tokens[0] !== 'true') {
            return $query;
        }
        try {
            $query = $query->onlyTrashed();
        } catch (\BadMethodCallException $e) {
            $apiException = new ApiException('Model does not support soft deletes');
            throw $apiException;
        }
        return $query;
    }
}