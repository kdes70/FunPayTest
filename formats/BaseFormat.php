<?php

namespace FpDbTest\formats;

use mysqli;

abstract class BaseFormat implements FormatContract
{
    public function __construct(protected mysqli $mysqli)
    {
    }

    abstract public function __invoke(mixed $value): string;
}