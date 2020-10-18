<?php


namespace Tests\Unit\Parsers;
use Illuminate\Database\Eloquent\Builder;
use Restive\Parsers\ParserOrWhereIn;
use Tests\Fixtures\Models\User;
use Tests\TestCase;

class ParserOrWhereInTests extends TestCase
{

    /** @test */

    public function instantiate_and_tokenize_query()
    {
        $parser = new ParserOrWhereIn(['values' => 'id:(3,4)']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function invalid_request_values()
    {
        $parser = new ParserOrWhereIn(['values' => '']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
    }

    /** @test */
    public function build_some_queries()
    {
        $parser = new ParserOrWhereIn(['values' => 'id:(1,10)']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertEquals('select * from "users" where ("id" in (?, ?)) and "users"."deleted_at" is null', $query->toSql());
    }

}