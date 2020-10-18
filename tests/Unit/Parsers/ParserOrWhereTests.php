<?php


namespace Tests\Unit\Parsers;
use Illuminate\Database\Eloquent\Builder;
use Restive\Parsers\ParserOrWhere;
use Tests\Fixtures\Models\User;
use Tests\TestCase;

class ParserOrWhereTests extends TestCase
{

    /** @test */

    public function instantiate_and_tokenize_query()
    {
        $parser = new ParserOrWhere(['values' => 'id:eq:1']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertInstanceOf(Builder::class, $query);
    }

    /** @test */
    public function invalid_request_values()
    {
        $parser = new ParserOrWhere(['values' => '']);
        $parser->tokenize();
        $this->assertTrue($parser->hasErrors());
    }

    /** @test */
    public function build_some_queries()
    {
        $parser = new ParserOrWhere(['values' => 'id:eq:1']);
        $model = new User();
        $parser->tokenize();
        $query = $parser->buildQuery($model->query());
        $this->assertEquals('select * from "users" where ("id" = ?) and "users"."deleted_at" is null', $query->toSql());
    }
}