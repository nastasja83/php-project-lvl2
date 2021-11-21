<?php

namespace Differ\Differ\Json;

use Exception;

/**
 * @param array $differTree
 *
 * @return string
 */
function format(array $differTree): string
{
    $result = json_encode($differTree, JSON_PRETTY_PRINT);
    if (is_string($result)) {
        return $result;
    }
    throw new Exception("Incorrect data, impossible to encode in JSON");
}
