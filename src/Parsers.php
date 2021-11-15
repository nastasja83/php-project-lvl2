<?php

namespace Differ\Differ;

use Exception;
use stdClass;
use Symfony\Component\Yaml\Yaml;

/**
 * @param string $content
 * @param string $type
 *
 * @return object
 */
function parse(string $content, string $type): object
{
    switch ($type) {
        case 'json':
            $parsed = json_decode($content);
            break;
        case 'yml':
        case 'yaml':
            $parsed = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            throw new Exception("Unsupported data type $type");
    }
    return $parsed;
}
