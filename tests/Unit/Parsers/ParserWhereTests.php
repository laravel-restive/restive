<?php

namespace Tests\Unit\Parsers;
use Illuminate\Database\Eloquent\Builder;
use Restive\Parsers\ParserWhere;
use Tests\Fixtures\Models\User;
use Tests\TestCase;

class ParserWhereTests extends TestCase
{

    /** @test */

    public function instantiate_and_tokenize_query()
    {
        $parser = new ParserWhere(['values' => 'id:eq:1']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function invalid_request_values()
    {
        $parser = new ParserWhere(['values' => '']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
    }

    /** @test */
    public function use_incorrect_validator()
    {
        $parser = new ParserWhere(['values' => 'id:eq:1']);
        $parser->setValidator(["null"]);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
    }

}