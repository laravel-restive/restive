<?php

namespace Restive\Controllers;

use Illuminate\Http\Request;
use Restive\ApiQueryParser;
use Restive\ParserFactory;
use Restive\ModelMakerFactory;
use Illuminate\Http\JsonResponse;
use Validator;

class ApiController extends AbstractApiController
{
    /**
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct(
        ModelMakerFactory $modelMaker
    ) {
        $this->model = $modelMaker->make($this->modelName);
    }

    public function index(Request $request): jsonResponse
    {
        try {
            $parser = new ApiQueryParser(new ParserFactory());
            $query = $parser->parseRequest($request)->buildparsers()->buildQuery($this->model);
            $count = $query->count();
            $defaultPerpage = 15;
            if ($request->input('paginate', 'yes') === 'no') {
                $defaultPerpage = $count;
            }
            $result = $query->paginate($request->input('per_page', $defaultPerpage));
        } catch (\Exception $e) {
            $message = $this->handleExceptionMessage($e);
            return $this->setStatusCode(400)->respondWithError($message);
        }
        return $this->respond($result->toArray());
    }

    public function store(Request $request): jsonResponse
    {
        $validator = Validator::make($request->all(), $this->loadRules());
        if ($validator->fails()) {
            return $this->setStatusCode(400)->respondWithError($validator->errors());
        }
        try {
            $result = $this->model->create($request->all());
        } catch (\Exception $e) {
            $message = $this->handleExceptionMessage($e);
            return $this->setStatusCode(400)->respondWithError($message);
        }
        return $this->respond([
            'data' => $result
        ]);
    }

    /**
     * @param mixed $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): jsonResponse
    {
        $this->convertIdToParserWhere($id, $request);
        try {
            $parser = new ApiQueryParser(new ParserFactory());
            $query = $parser->parseRequest($request)->buildparsers()->buildQuery($this->model);
        } catch (\Exception $e) {
            $message = $this->handleExceptionMessage($e);
            return $this->setStatusCode(400)->respondWithError($message);
        }
        $result = $query->get();
        if ($result->count() === 0) {
            return $this->setStatusCode(400)->respondWithError('Item does not exist');
        }
        $result = $result->first();
        return $this->respond([
            'data' => $result->toArray()
        ]);
    }

    public function destroy(Request $request, $id = null): jsonResponse
    {
        $this->convertIdToParserWhere($id, $request);
        try {
            $parser = new ApiQueryParser(new ParserFactory());
            $query = $parser->parseRequest($request)->buildparsers()->buildQuery($this->model);
        } catch (\Exception $e) {
            $message = $this->handleExceptionMessage($e);
            return $this->setStatusCode(400)->respondWithError($message);
        }
        $result = $query->get();
        $query->each(function ($record) {
            $record->delete();
        });
        return $this->respond([
            'data' => $result->toArray()
        ]);
    }

    public function update(Request $request, $id = null): jsonResponse
    {
        $this->convertIdToParserWhere($id, $request);
        if (isset($id)) {
            $validator = Validator::make($request->all(), $this->loadRules($id));
            if ($validator->fails()) {
                return $this->setStatusCode(400)->respondWithError($validator->errors());
            }
        }
        try {
            $parser = new ApiQueryParser(new ParserFactory());
            $query = $parser->parseRequest($request)->buildparsers()->buildQuery($this->model);
            $request = $this->stripQueryParams($request);
            $result = $query->update($request->all());
        } catch (\Exception $e) {
            $message = $this->handleExceptionMessage($e);
            return $this->setStatusCode(400)->respondWithError($message);
        }
        return $this->respond([
            'data' => 'affected rows = ' . $result
        ]);
    }

    /**
     * @param mixed $id
     * @return array
     */
    protected function loadRules($id = 0)
    {
        if (method_exists($this->model, 'rules')) {
            return $this->model->rules($id);
        }
        return [];
    }

    protected function stripQueryParams(Request $request)
    {
        $request = Request::create('/', 'GET', $request->request->all());
        return $request;
    }

    protected function convertIdToParserWhere($id, Request $request)
    {
        if (!isset($id)) {
            return $request;
        }
        $key = $this->model->getKeyName();
        $where = $key . ':eq:' . $id;
        $request->query->add(['where' => [$where]]);
        return $request;
    }
}
