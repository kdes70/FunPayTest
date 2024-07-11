<?php

namespace FpDbTest\formats;

use Exception;

class FormatArray extends BaseFormat
{

    /**
     * @throws Exception
     */
    public function __invoke(mixed $value): string
    {
        if (!is_array($value)) {
            throw new Exception("Array expected for ?a parameter");
        }

        if (empty($value)) {
            return '';
        }
        if (array_keys($value) === range(0, count($value) - 1)) {
            return implode(', ', array_map(fn($v) => (new FormatValue($this->mysqli))($v), $value));
        }
        return implode(', ', array_map(fn($k, $v) => (new FormatIdentifier($this->mysqli))($k) . ' = ' . (new FormatValue($this->mysqli))($v), array_keys($value), $value));

    }
}