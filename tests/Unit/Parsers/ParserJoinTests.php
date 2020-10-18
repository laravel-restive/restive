<?php


namespace Tests\Unit\Parsers;
use Illuminate\Database\Eloquent\Builder;
use Restive\Parsers\ParserJoin;
use Tests\Fixtures\Models\User;
use Tests\TestCase;

class ParserJoinTests extends TestCase
{

    /** @test */
    public function instantiate_and_tokenize_query()
    {
        $parser = new ParserJoin(['values' => 'inner:table:keyLeft:keyRight']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function invalid_request_values()
    {
        $parser = new ParserJoin(['values' => '']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
    }

    /** @test */
    public function build_some_queries()
    {
        $parser = new ParserJoin(['values' => 'inner:table:keyLeft:keyRight']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertEquals('select * from "users" inner join "table" on "keyLeft" = "keyRight" where "users"."deleted_at" is null', $query->toSql());
        //dump($query->toSql());
    }
}