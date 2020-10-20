<?php declare(strict_types=1);

namespace Restive;

use Illuminate\http\Request;
use Restive\Contracts\RequestParser;
use Restive\Exceptions\ApiException;
use Restive\Parsers\ParserNull;

class ApiQueryParser
{
    public function buildParseKeys(Request $request) : array
    {
        $queryString = rawurldecode($request->server()['QUERY_STRING']);
        dump($queryString);
        $parsedQuery = $this->parseQueryString($queryString);
        return $parsedQuery;
    }

    public function buildParserList(array $parsedQuery) : array
    {
        $parserList = [];
        foreach ($parsedQuery as $query) {
            $parserList[] = $this->parserFactory($query[0], $query[1]);
        }
        return $parserList;
    }

    public function executeParsers(array $parsers, $model)
    {
        $query = $model->query();
        foreach ($parsers as $parser) {
            try {
                $this->parserParseValueTokens($parser);
            } catch (ApiException $e) {
                continue;
            }
            $query = $parser->buildQuery($query);
        }
        return $query;
    }

    protected function parseQueryString(string $queryString) : array
    {
        $ignoreKeys = ['page', 'per_page', 'paginate'];
        $queryParameters = [];
        if (trim($queryString) === '') {
            return $queryParameters;
        }
        $queryParts = explode('&', trim($queryString));
        foreach ($queryParts as $queryPart) {
            $parts = explode('=', $queryPart);
            $queryKey = $parts[0] ?? '';
            if (in_array($queryKey, $ignoreKeys)) {
                continue;
            }
            $queryKey = rtrim($queryKey, '[]');
            $queryValue = $parts[1] ?? '';
            $queryParameters[] = [$queryKey, $queryValue];
        }
        return $queryParameters;
    }

    protected function parserParseValueTokens(RequestParser $parser) : bool
    {
        $parser->tokenize();
        if (!$parser->hasErrors()) {
            return true;
        }
        return false;
    }

    protected function parserFactory($action, $parameters)
    {
        $blacklist = config('restive.blacklist');
        if (in_array($action, $blacklist)) {
            return new ParserNull(['error' => 'blacklisted method - ' . $action]);
        }
        $classname = __NAMESPACE__ . '\\Parsers\\' . 'Parser' . ucfirst($action);
        if (!class_exists($classname)) {
            throw new ApiException('unknown parser method - ' . $action);
        }
        $class = new $classname(['values' => $parameters]);
        return $class;
    }

}