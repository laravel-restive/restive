<?php


namespace Tests\Unit\Parsers;
use Illuminate\Database\Eloquent\Builder;
use Restive\Parsers\ParserSort;
use Tests\Fixtures\Models\User;
use Tests\TestCase;

class ParserSortTests extends TestCase
{

    /** @test */

    public function instantiate_and_tokenize_query()
    {
        $parser = new ParserSort(['values' => 'foo,-bar']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function invalid_request_values()
    {
        $parser = new ParserSort(['values' => '']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
    }

    /** @test */
    public function build_some_queries()
    {
        $parser = new ParserSort(['values' => 'foo,-bar']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertEquals('select * from "users" where "users"."deleted_at" is null order by "foo" asc, "bar" desc', $query->toSql());
    }

}