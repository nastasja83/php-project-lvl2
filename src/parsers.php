<?php

namespace Differ\Differ;

/**
 * JSON will be returned as associative array

 * @param string $file string

 * @return mixed
 */
function jsonParse(string $file)
{
    $data = json_decode($file, true, 512, JSON_OBJECT_AS_ARRAY);
    if (json_last_error() !== 0) {
        return json_last_error_msg();
    }
    return $data;
}
