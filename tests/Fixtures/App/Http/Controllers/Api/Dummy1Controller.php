<?php
namespace Tests\Fixtures\App\Http\Controllers\Api;

use Restive\Http\Controllers\ApiController;

class Dummy1Controller extends ApiController
{
    protected $modelName = 'Dummy1';
    protected $resource = 'Tests\\Fixtures\\App\\Http\\Resources\\DummyResource';
    protected $resourceCollection = 'Tests\\Fixtures\\App\\Http\\Resources\\DummyResourceCollection';
    protected $request = 'Tests\\Fixtures\\App\\Http\\Requests\\DummyRequest';
}