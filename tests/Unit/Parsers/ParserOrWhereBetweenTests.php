<?php


namespace Tests\Unit\Parsers;
use Illuminate\Database\Eloquent\Builder;
use Restive\Parsers\ParserOrWhereBetween;
use Tests\Fixtures\Models\User;
use Tests\TestCase;

class ParserOrWhereBetweenTests extends TestCase
{

    /** @test */

    public function instantiate_and_tokenize_query()
    {
        $parser = new ParserOrWhereBetween(['values' => 'id:1:10']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function invalid_request_values()
    {
        $parser = new ParserOrWhereBetween(['values' => '']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
    }

    /** @test */
    public function build_some_queries()
    {
        $parser = new ParserOrWhereBetween(['values' => 'id:1:10']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertEquals('select * from "users" where ("id" between ? and ?) and "users"."deleted_at" is null', $query->toSql());
    }

}