<?php

namespace Differ\Differ\Formatters;

function toString($value): string
{
     return trim(var_export($value, true), "'");
}
