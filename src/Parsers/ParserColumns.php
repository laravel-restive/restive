<?php
declare(strict_types = 1);

namespace Restive\Parsers;

use Illuminate\Database\Eloquent\Builder;

class ParserColumns extends ParserAbstract
{
    protected $validator = ['separated', ',', null];

    public function buildQuery(Builder $query) : Builder
    {
        foreach ($this->tokens as $token) {
            $query = $query->addSelect($token);
        }
        return $query;
    }
}
