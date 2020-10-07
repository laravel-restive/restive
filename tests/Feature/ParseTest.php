<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Tests\Fixtures\Controllers\Api\ZcwiltUserController;
use Restive\ModelMakerFactory;
use Illuminate\Support\Facades\Request;
use Tests\DatabaseTestCase;

class ParseTest extends DatabaseTestCase
{
    /** @test */
    public function unknown_parser_method_throws_exception()
    {
        $request = Request::create('/index', 'GET', [
            'title' => 'foo',
            'text' => 'bar',
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $this->assertTrue($response->getStatusCode() === 400);
        $this->assertTrue(json_decode($response->getContent())->error->message === "Can't find parser class Restive\Parsers\ParserTitle");
    }

    public function testControllerIndexNoParser()
    {
        $request = Request::create('/index', 'GET', [
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 15); //default pagination = 15
    }
    
    public function testControllerIndexWithWhereParser()
    {
        $request = Request::create('/index', 'GET', [
            'where' => 'id:eq:2'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 1);
        $this->assertTrue($response->data[0]->id === 2);
    }

    public function testControllerIndexWithWhereInParser()
    {
        $request = Request::create('/index', 'GET', [
            'whereIn' => 'id:(1,2)'
        ]);
        $controller = new ZcwiltUserController(new ModelMakerFactory());
        $response = $controller->index($request);
        $response = json_decode($response->getContent());
        $this->assertTrue(count($response->data) === 2);
        $this->assertTrue($response->data[0]->id === 1);
    }

    /** @test */
     public function blacklisted_parser_method_throws_exception()
     {
         Config::set('restive.blacklist', ['where']);
         $response = $this->get("/user?where[]=id:eq:1");
         $response->assertStatus(400);
         $message = json_decode($response->getContent())->error->message;
         $this->assertStringContainsString('Parser method not allowed', $message);
     }
}
