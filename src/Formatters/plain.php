<?php

namespace Differ\Differ\Formatters;

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

 * @param array $tree differTree
 * @param array<mixed> $tree

 * @return string
 */
function format(array $tree): string
{
    $lines = array_map(function ($item) {
        $key = $item['key'];
        $type = $item['type'];

        switch ($type) {
            case 'deleted':
                $value = $item['value'];
                $line = "  - {$key}: {$value}";
                break;
            case 'unchanged':
                $value = $item['value'];
                $line = "    {$key}: {$value}";
                break;
            case 'added':
                $value = $item['value'];
                $line = "  + {$key}: {$value}";
                break;
            case 'changed':
                $oldValue = $item['oldValue'];
                $newValue = $item['newValue'];
                $line = "  - {$key}: {$oldValue}\n  + {$key}: {$newValue}";
                break;
            default:
                throw new Exception('Unknown type of item');
        }
        return $line;
    }, $tree);
    $unitedLines = implode("\n", $lines);
    $result =  "\n{\n{$unitedLines}\n}";
    return $result;
}
