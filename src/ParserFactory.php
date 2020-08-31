<?php

namespace Restive;

use Restive\Exceptions\UnknownParserException;
use Restive\Exceptions\BlacklistedParserException;
use Restive\Parsers\ParserInterface;

class ParserFactory
{
    public function getParser(string $method): ParserInterface
    {
        $blacklist = config('restive.blacklist');
        if (in_array($method, $blacklist)) {
            throw new BlacklistedParserException("Parser method not allowed " . $method);
        }
        $classname = __NAMESPACE__ . '\\Parsers\\' . 'Parser' . ucfirst($method);
        if (!class_exists($classname)) {
            throw new UnknownParserException("Can't find parser class " . $classname);
        }
        $class = new $classname();
        return $class;
    }
}
