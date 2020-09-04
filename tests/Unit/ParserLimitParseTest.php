<?php

namespace Tests\Unit;

use Restive\ApiQueryParser;
use Restive\Exceptions\ParserParameterCountException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Restive\ParserFactory;
use Tests\DatabaseTestCase;
use Illuminate\Support\Facades\Request;
use Tests\Fixtures\Controllers\Api\ZcwiltUserController;
use Restive\ModelMakerFactory;
use Tests\Fixtures\Models\ZcwiltUser;

class ParserLimitParseTest extends DatabaseTestCase
{
    public function testLimitParserParseTestNoParams()
    {
        $parserFactory = new ParserFactory();
        $parser = $parserFactory->getParser('limit');
        $this->expectException(ParserParameterCountException::class);
        $parser->parse('');
    }

    public function testLimitParserParseTestWithParams()
    {
        $api = new ApiQueryParser(new ParserFactory());
        Request::instance()->query->set('limit', '1');
        $api->parseRequest(Request::instance());
        $api->buildParsers();
        $tokenized = $api->getQueryParts()[0]->getTokenized()[0];
        $this->assertTrue($tokenized['field'] === '1');
    }

}
