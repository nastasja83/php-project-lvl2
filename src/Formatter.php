<?php

namespace Differ\Differ;

use Exception;

/**
 * @param string $format
 * @param array $differTree
 *
 * @return string
 */
function formatDiff(string $format, array $differTree): string
{
    switch ($format) {
/*        case 'plain':
            $formatted = Plain\format($differTree);
            break;
        case 'json':
            $formatted = Json\format($differTree);
            break;*/
        case 'stylish':
            $formatted = Stylish\format($differTree);
            break;
        default:
            throw new Exception("Unknown format $format");
    }
    return $formatted;
}
