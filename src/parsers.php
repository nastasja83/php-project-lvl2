<?php

namespace Differ\Differ;

function jsonParse($file)
{
    $data = json_decode($file, null, 512, JSON_OBJECT_AS_ARRAY);
    if (json_last_error() !== 0) {
        return json_last_error_msg();
    }
    return $data;
}

function toString($value)
{
     return trim(var_export($value, true), "'");
}
