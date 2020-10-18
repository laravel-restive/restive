<?php


namespace Tests\Unit\Parsers;
use Illuminate\Database\Eloquent\Builder;
use Restive\Parsers\ParserWhereIn;
use Tests\Fixtures\Models\User;
use Tests\TestCase;

class ParserWhereInTests extends TestCase
{

    /** @test */

    public function instantiate_and_tokenize_query()
    {
        $parser = new ParserWhereIn(['values' => 'id:(3,4)']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function invalid_request_values()
    {
        $parser = new ParserWhereIn(['values' => '']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
        $parser = new ParserWhereIn(['values' => 'id:']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
        $parser = new ParserWhereIn(['values' => 'id:()']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
    }

    /** @test */
    public function build_some_queries()
    {
        $parser = new ParserWhereIn(['values' => 'id:(1,10)']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertEquals('select * from "users" where "id" in (?, ?) and "users"."deleted_at" is null', $query->toSql());
    }
}