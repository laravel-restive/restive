<?php declare(strict_types=1);

namespace Restive\Http\Controllers;

use Restive\ApiQueryParser;
use Restive\Api\ParserFactory;
use Restive\Exceptions\ApiException;
use Restive\Http\Requests\Request;

class ApiController extends AbstractApiController
{
    public function index(Request $request)
    {
        $parser = new ApiQueryParser();
        try {
            $parsedKeys = $parser->buildParseKeys($request);
            $parserList = $parser->buildParserList($parsedKeys);
            $query = $parser->executeParsers($parserList, $this->model);
            $paginatedQuery = $this->paginate($query, $request);
            $resource = new $this->resourceCollection($paginatedQuery);
        } catch (ApiException $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getStatusCode());
        }
        return $resource->toJson();
    }

    public function show(Request $request, $id)
    {
        $parser = new ApiQueryParser();
        $parsedKeys = $parser->buildParseKeys($request);
        $parsedKeys = $this->convertIdToParserWhere($id, $parsedKeys);
        $parserList = $parser->buildParserList($parsedKeys);
        $query = $parser->executeParsers($parserList, $this->model);
        $query = $query->first();
        if (is_null($query)) {
            return response()->json(['error' => 'Item does not exist'], 404);
        }
        $resource = new $this->resource($query);
        return $resource->toJson();
    }

    public function store(Request $request)
    {
        $result = $this->model->create($request->all());
        $resource = new $this->resource($result);
        return $resource->toJson();
    }

    public function update(Request $request, $id = null)
    {
        $parser = new ApiQueryParser();
        $parsedKeys = $parser->buildParseKeys($request);
        $parsedKeys = $this->convertIdToParserWhere($id, $parsedKeys);
        $parserList = $parser->buildParserList($parsedKeys);
        $query = $parser->executeParsers($parserList, $this->model);
        $request = $this->stripQueryParams($request);
        $result = $query->update($request->all());
        return json_encode(['affected_rows' => $result]);
    }

    public function destroy(Request $request, $id = null)
    {
        $parser = new ApiQueryParser();
        try {
            $parsedKeys = $parser->buildParseKeys($request);
            $parsedKeys = $this->convertIdToParserWhere($id, $parsedKeys);
            $parserList = $parser->buildParserList($parsedKeys);
            $query = $parser->executeParsers($parserList, $this->model);
            $result = $query->get();
            $query->each(
                function ($record) {
                    $record->delete();
                }
            );
        } catch (ApiException $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getStatusCode());
        }
        $resource = new $this->resource($result);
        return $resource->toJson();
    }
}