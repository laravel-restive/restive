<?php
declare(strict_types = 1);

namespace Restive\Parsers;

use Restive\Contracts\RequestParser;

abstract class ParserAbstract implements RequestParser
{
    protected $tokens = [];

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        if (!isset($this->parameters['values'])) {
            $this->parameters['values'] = '';
        }
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getErrors()
    {
        return $this->parameters['error'];
    }

    public function hasErrors()
    {
        return (isset($this->parameters['error'])) ? true : false;
    }

    public function tokenize()
    {
        $this->tokens = $this->parseTokens($this->parameters['values'], $this->validator);
        $this->validateTokens($this->tokens, $this->validator);
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;
    }

    protected function validateTokens(array $tokens, array $validator)
    {
        $type = $validator[0];
        $validatorHandler = 'handleValidator' . ucfirst(strtolower($type));
        if (!method_exists($this, $validatorHandler)) {
            $this->addError('Invalid validator type  - ' . $type);
            return false;
        }
        if (!$this->{$validatorHandler}($tokens, $validator)) {
            return false;
        }
        return true;
    }

    protected function parseTokens(string $parameters, array $validator)
    {
        $type = $validator[0];
        $tokens = [];
        $parseTokenHandler = 'handle' . ucfirst(strtolower($type)) . 'Tokenizer';
        if (!method_exists($this, $parseTokenHandler)) {
            $this->addError('Invalid validator handler  - ' . $type);
            return $tokens;
        }
        $tokens = $this->{$parseTokenHandler}($parameters, $validator);
        return $tokens;
    }

    protected function addError(string $error)
    {
        $this->parameters['error'][] = $error;
    }

    protected function handleSeparatedTokenizer(string $parameters, array $validator)
    {
        $tokens = explode($validator[1], $parameters);
        return $tokens;
    }

    protected function handleValidatorSeparated(array $tokens, array $validator)
    {
        if (!is_null($validator[2]) && count($tokens) != $validator[2]) {
            $this->addError('invalid number of options');
            return false;
        }
        if (is_null($validator[2]) && empty($tokens[0])) {
            $this->addError('invalid number of options');
            return false;
        }
        return true;
    }

    protected function handleValidatorBoolean(array $tokens, array $validator)
    {
        return true;
    }


protected function handleBooleanTokenizer($parameters, $validator)
    {
        $tokens = [$parameters];
        return $tokens;
    }

    protected function handleBracketedTokenizer(string $parameters, array $validator)
    {
        $tokens = [];
        $parts = explode(':', $parameters);
        if (count($parts) !== 2) return $tokens;
        $tokens['col'] = $parts[0];
        $tokens['in'] = explode($validator[1], str_replace(['(',')'], '', $parts[1]));
        return $tokens;
    }

    protected function handleValidatorBracketed(array $tokens, array $validator)
    {
        if (!isset($tokens['col'])) {
            $this->addError('invalid options for whereIn clause');
            return false;
        }
        if (empty($tokens['in'][0])) {
            $this->addError('invalid options for whereIn clause');
            return false;
        }
        return true;
    }
}