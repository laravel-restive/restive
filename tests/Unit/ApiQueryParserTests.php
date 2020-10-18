<?php


namespace Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Restive\ApiQueryParser;
use Restive\Exceptions\ApiException;
use Restive\Parsers\ParserNull;
use Restive\Parsers\ParserWhere;
use Restive\Parsers\ParserWith;
use Tests\Fixtures\Models\User;
use Tests\TestCase;

class ApiQueryParserTests extends TestCase
{

    /** @test */
    public function instantiates_apiqueryparser()
    {
        $parser = new ApiQueryParser();
        $this->assertInstanceOf(ApiQueryParser::class, $parser);
    }

    /** @test */
    public function build_parse_keys_with_empty_request()
    {
        $parser = new ApiQueryParser();
        $request = Request::create('/index', 'GET');
        $keys = $parser->buildParseKeys($request);
        $this->assertCount(0, $keys);
    }

    /** @test */
    public function build_parse_keys_with_request_query_single()
    {
        $parser = new ApiQueryParser();
        $request = Request::create('/index?where[]=id:eq:1', 'GET');
        $keys = $parser->buildParseKeys($request);
        $this->assertCount(1, $keys);
        $this->assertCount(2, $keys[0]);
    }

    /** @test */
    public function build_parse_keys_with_request_query_multiple()
    {
        $parser = new ApiQueryParser();
        $request = Request::create('/index?where[]=id:eq:1&where[]=id:eq:2&with[]=posts', 'GET');
        $keys = $parser->buildParseKeys($request);
        $this->assertCount(3, $keys);
        $this->assertCount(2, $keys[0]);
        $this->assertEquals('where', $keys[0][0]);
    }

    /** @test */
    public function build_parser_list_with_empty_request()
    {
        $parser = new ApiQueryParser();
        $request = Request::create('/index', 'GET');
        $keys = $parser->buildParseKeys($request);
        $parsers = $parser->buildParserList($keys);
        $this->assertCount(0, $parsers);
    }

    /** @test */
    public function build_parse_list_with_request_query_single()
    {
        $parser = new ApiQueryParser();
        $request = Request::create('/index?foo[]=id:eq:1', 'GET');
        $keys = $parser->buildParseKeys($request);
        $this->expectExceptionMessage('unknown parser method - foo');
        $parsers = $parser->buildParserList($keys);
    }

    /** @test */
    public function build_parse_list_with_request_query_multiple()
    {
        $parser = new ApiQueryParser();
        $request = Request::create('/index?where[]=id:eq:1&where[]=id:eq:2&with[]=posts', 'GET');
        $keys = $parser->buildParseKeys($request);
        $parsers = $parser->buildParserList($keys);
        $this->assertCount(3, $parsers);
        $this->assertInstanceOf(ParserWhere::class, $parsers[0]);
        $this->assertInstanceOf(ParserWhere::class, $parsers[1]);
        $this->assertInstanceOf(ParserWith::class, $parsers[2]);
    }

    /** @test */
    public function build_parse_list_with_blacklisted_query()
    {
        Config::set('restive.blacklist', ['where']);
        $parser = new ApiQueryParser();
        $request = Request::create('/index?where[]=id:eq:1', 'GET');
        $keys = $parser->buildParseKeys($request);
        $parsers = $parser->buildParserList($keys);
        $this->assertCount(1, $parsers);
        $this->assertInstanceOf(ParserNull::class, $parsers[0]);
        $parserParams = $parsers[0]->getParameters();
        $this->assertStringContainsString('blacklisted method', $parserParams['error']);
    }

    /** @test */
    public function execute_parsers_with_request_query_single()
    {
        $model = new User();
        $parser = new ApiQueryParser();
        $request = Request::create('/index?where[]=id:eq:1', 'GET');
        $keys = $parser->buildParseKeys($request);
        $parsers = $parser->buildParserList($keys);
        $query = $parser->executeParsers($parsers, $model);
        $this->assertStringContainsString('select * from "users" where "id" = ? and "users"."deleted_at" is null', $query->toSql());
    }
}