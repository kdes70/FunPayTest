<?php

namespace FpDbTest\formats;

use mysqli;

interface FormatContract
{
    public function __invoke(mixed $value): string;
}