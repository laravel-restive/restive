<?php
namespace Tests\Unit;

use Tests\Fixtures\Controllers\Api\ZcwiltDummyController;
use Restive\ApiQueryParser;
use Restive\ParserFactory;
use Tests\TestCase;
use Restive\Facades\Restive;

class InstantiationTest extends TestCase
{
    public function testSimple()
    {
        $api = new ApiQueryParser(new ParserFactory());
        $queryParts = $api->getQueryParts();
        $this->assertTrue(is_array($queryParts));
        $this->assertTrue(count($queryParts) === 0);
        $parserFactory = $api->getParserFactory();
        $this->assertTrue($parserFactory instanceof ParserFactory);

        Restive::resource('ZcwiltDummy', ZcwiltDummyController::class);
    }

    public function testRoutes()
    {
        $routes = $this->getRouteEntries();
        $this->assertTrue(collect($routes)->pluck('method')->count() === 14);
    }
}
