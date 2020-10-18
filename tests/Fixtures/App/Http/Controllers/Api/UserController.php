<?php
namespace Tests\Fixtures\App\Http\Controllers\Api;

use Restive\Http\Controllers\ApiController;

class UserController extends ApiController
{
    protected $modelName = '\\Tests\Fixtures\\Models\\User';
    protected $request = '\\Tests\Fixtures\\App\\Http\\Requests\\UserRequest';

    public function authorize($ability, $arguments = [])
    {
        return true;
    }
}
