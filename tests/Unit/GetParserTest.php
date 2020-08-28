<?php

namespace Tests\Unit;

use Restive\Exceptions\UnknownParserException;
use Restive\ParserFactory;
use Tests\TestCase;

class GetParserTest extends TestCase
{
    public function testFailParserFactory()
    {
        $parserFactory = new ParserFactory();
        $this->expectException(UnknownParserException::class);
        $parserFactory->getParser('action');
    }

    public function testPassParserFactory()
    {
        $parserFactory = new ParserFactory();
        $result = $parserFactory->getParser('sort');
        $this->assertTrue(count($result->getTokenized()) === 0);
    }
}
