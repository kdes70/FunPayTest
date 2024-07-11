<?php

namespace FpDbTest\formats;

use Exception;

class FormatValue extends BaseFormat
{
    /**
     * @throws Exception
     */
    public function __invoke(mixed $value): string
    {
        return match (true) {
            $value === null => 'NULL',
            is_bool($value) => $value ? '1' : '0',
            is_int($value) || is_float($value) => (string)$value,
            is_string($value) => "'" . $this->mysqli->real_escape_string($value) . "'",
            default => throw new Exception("Unsupported value type: " . gettype($value)),
        };
    }
}