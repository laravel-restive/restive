<?php

namespace Restive;

use Restive\Exceptions\UnknownParserException;
use Restive\Parsers\ParserInterface;

class ParserFactory
{
    public function getParser(string $method): ParserInterface
    {
        $classname = __NAMESPACE__ . '\\Parsers\\' . 'Parser' . ucfirst($method);
        if (!class_exists($classname)) {
            throw new UnknownParserException("Can't find parser class " . $classname);
        }
        $class = new $classname();
        return $class;
    }
}
