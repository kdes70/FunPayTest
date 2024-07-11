<?php

namespace FpDbTest\formats;

use mysqli;

class FormatIdentifier extends BaseFormat
{

    public function __invoke(mixed $value): string
    {
        if (is_array($value)) {
            return implode(', ', array_map([$this, '__invoke'], $value));
        }
        return '`' . str_replace('`', '``', $value) . '`';
    }
}