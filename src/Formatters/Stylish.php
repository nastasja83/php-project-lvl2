<?php

namespace Differ\Differ\Stylish;

use Exception;

/**
 * Transform $value to string

 * @param mixed $value bool|array|int

 * @return string
 */
function toString($value): string
{
     return trim(var_export($value, true), "'");
}
/**
 * Transform $differTree to string

 * @param array<mixed> $tree differTree

 * @return string
 */
function format(array $tree): string
{
    $lines = array_map(function ($item) {
        $key = $item['key'];
        $type = $item['type'];

        switch ($type) {
            case 'deleted':
                $value = toString($item['value']);
                $line = "  - {$key}: {$value}";
                break;
            case 'unchanged':
                $value = toString($item['value']);
                $line = "    {$key}: {$value}";
                break;
            case 'added':
                $value = toString($item['value']);
                $line = "  + {$key}: {$value}";
                break;
            case 'changed':
                $oldValue = toString($item['oldValue']);
                $newValue = toString($item['newValue']);
                $line = "  - {$key}: {$oldValue}\n  + {$key}: {$newValue}";
                break;
            default:
                throw new Exception('Unknown type of item');
        }
        return $line;
    }, $tree);
    $unitedLines = implode("\n", $lines);
    $result =  "\n{\n{$unitedLines}\n}\n";
    return $result;
}
