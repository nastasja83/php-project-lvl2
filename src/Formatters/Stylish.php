<?php

namespace Differ\Differ\Stylish;

use Exception;

const INDENT_LENGTH = 4;

function getIndent(int $depth): string
{
    return str_repeat(' ', INDENT_LENGTH * $depth);
}

/**
 * Transform $value to string

 * @param mixed $value bool|array|int

 * @return string
 */
function toString($value): string
{
    if ($value === null) {
        return 'null';
    }
    return trim(var_export($value, true), "'");
}
/**
 * @param mixed $value
 * @param int $depth
 *
 * @return string
 */
function stringify($value, int $depth): string
{
    if (!is_object($value)) {
        return toString($value);
    }

    $stringifyValue = function ($currentValue, $depth): string {
        $indent = getIndent($depth);
        $iter = function ($value, $key) use ($depth, $indent): string {
            $formattedValue = stringify($value, $depth);
            return "{$indent}    {$key}: {$formattedValue}";
        };

        $stringifiedValue = array_map($iter, (array) $currentValue, array_keys((array) $currentValue));
        return implode("\n", ["{", ...$stringifiedValue, "{$indent}}"]);
    };
    return $stringifyValue($value, $depth + 1);
}
/**
 * Transform $differTree to string

 * @param array<mixed> $tree differTree

 * @return string
 */
function format(array $tree, int $depth = 0): string
{
    $indent = getIndent($depth);
    $lines = array_map(function ($item) use ($indent, $depth) {
        $key = $item['key'];
        $type = $item['type'];

        switch ($type) {
            case 'deleted':
                $value = stringify($item['value'], $depth);
                return "{$indent}  - {$key}: {$value}";
            case 'unchanged':
                $value = stringify($item['value'], $depth);
                return "{$indent}    {$key}: {$value}";
            case 'added':
                $value = stringify($item['value'], $depth);
                return "{$indent}  + {$key}: {$value}";
            case 'changed':
                $oldValue = stringify($item['oldValue'], $depth);
                $newValue = stringify($item['newValue'], $depth);
                return "{$indent}  - {$key}: {$oldValue}\n{$indent}  + {$key}: {$newValue}";
            case 'parent':
                $value = format($item['children'], $depth + 1);
                return "{$indent}    {$key}: {$value}";
            default:
                throw new Exception('Unknown type of item');
        }
    }, $tree);
    return implode("\n", ["{", ...$lines, "{$indent}}"]);
}
