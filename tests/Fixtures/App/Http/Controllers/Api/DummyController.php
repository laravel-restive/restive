<?php
namespace Tests\Fixtures\App\Http\Controllers\Api;

use Restive\Http\Controllers\ApiController;

class DummyController extends ApiController
{
    protected $modelName = '\\Tests\Fixtures\\Models\\Dummy';
}
