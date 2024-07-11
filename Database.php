<?php

namespace FpDbTest;

use Exception;
use FpDbTest\formats\FormatArray;
use FpDbTest\formats\FormatIdentifier;
use FpDbTest\formats\FormatValue;
use mysqli;

class Database implements DatabaseInterface
{
    private const SKIP_VALUE = '___SKIP___';
    private const PATTERN_MAIN = '/\{[^{}]*\}|\?[#dfа]?/';
    private const PATTERN_CONDITIONAL = '/\?[#dfа]?/';

    public function __construct(private mysqli $mysqli)
    {
    }

    public function buildQuery(string $query, array $args = []): string
    {
        $index = 0;
        return preg_replace_callback(self::PATTERN_MAIN, fn($match) => $this->processMatch($match[0], $args, $index), $query);
    }

    public function skip()
    {
        return self::SKIP_VALUE;
    }

    private function processMatch(string $param, array $args, int &$index): string
    {
        if ($param[0] === '{') {
            return $this->processConditionalBlock(substr($param, 1, -1), $args, $index);
        }

        if (!isset($args[$index])) {
            throw new Exception("Not enough arguments for query");
        }

        $value = $args[$index++];
        return $this->formatParameter($param, $value);
    }

    private function processConditionalBlock(string $block, array $args, int &$index): string
    {
        $tempIndex = $index;
        $result = preg_replace_callback(self::PATTERN_CONDITIONAL, fn($match) => $this->processConditionalParam($match[0], $args, $tempIndex), $block);

        if (str_contains($result, self::SKIP_VALUE)) {
            $index = $tempIndex;
            return '';
        }

        $index = $tempIndex;
        return $result;
    }

    /**
     * @throws Exception
     */
    private function processConditionalParam(string $param, array $args, int &$tempIndex): string
    {
        if (!isset($args[$tempIndex])) {
            throw new Exception("Not enough arguments for query");
        }
        $value = $args[$tempIndex++];
        return $value === self::SKIP_VALUE ? self::SKIP_VALUE : $this->formatParameter($param, $value);
    }

    /**
     * @throws Exception
     */
    private function formatParameter(string $param, mixed $value): string
    {
        return match ($param) {
            SpecifiersEnum::DEFAULT_TYPE->value => (new FormatValue($this->mysqli))($value),
            SpecifiersEnum::INTEGER->value => $value === null ? 'NULL' : (int)$value,
            SpecifiersEnum::FLOAT->value => $value === null ? 'NULL' : (float)$value,
            SpecifiersEnum::ARRAY->value => (new FormatArray($this->mysqli))($value),
            SpecifiersEnum::IDENTIFIER->value => (new FormatIdentifier($this->mysqli))($value),
            default => throw new Exception("Unknown parameter type: $param"),
        };
    }
}