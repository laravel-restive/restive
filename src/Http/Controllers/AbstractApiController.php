<?php declare(strict_types=1);

namespace Restive\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Restive\ApiQueryParser;
use Restive\ComponentFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Restive\Http\Requests\Request;

class AbstractApiController extends \Illuminate\Routing\Controller
{

    protected $model;
    protected $modelName = '';
    protected $resource = '';
    protected $resourceCollection = '';
    protected $request = '';
    protected $parser;
    protected $componentFactory;
    protected $paginator = '';

    use AuthorizesRequests;

    public function __construct(ApiQueryParser $apiQueryParser, ComponentFactory $componentFactory)
    {
        $this->componentFactory = $componentFactory;
        $this->model = $this->componentFactory->resolveModel($this->modelName);
        $this->resource = $this->componentFactory->resolveResource($this->model, $this->resource);
        $this->resourceCollection = $this->componentFactory->resolveResourceCollection($this->model, $this->resourceCollection);
        $this->request = $this->componentFactory->resolveRequest($this->model, $this->request);
        $this->componentFactory->bindRequestClass($this->request);
        $this->parser = $apiQueryParser;
        $this->paginator = $this->componentFactory->resolvePaginator($this->paginator);
    }

    public function paginate(Builder $query, Request $request)
    {
        $paginator = new $this->paginator();
        $result = $paginator->paginate($query, $request);
        return $result;
    }

    public function getRequest() : string
    {
        return $this->request;
    }

    protected function convertIdToParserWhere($id, array $parsedKeys) : array
    {
        if (!isset($id)) {
            return $parsedKeys;
        }
        $key = $this->model->getKeyName();
        $where = $key . ':eq:' . $id;
        $parsedKeys[] = ['where', $where];
        return $parsedKeys;
    }

    protected function stripQueryParams(Request $request) : Request
    {
        $request = Request::create('/', 'GET', $request->request->all());
        return $request;
    }
}