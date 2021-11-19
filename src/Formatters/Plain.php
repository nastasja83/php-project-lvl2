<?php

namespace Differ\Differ\Plain;

use Exception;

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
    if (is_object($value)) {
        return "[complex value]";
    }
    return var_export($value, true);
}

/**
 * @param array $tree
 * @param array $propertyNames
 *
 * @return string
 */
function format(array $tree, array $propertyNames = []): string
{
    $lines = array_map(function ($item) use ($propertyNames) {
        $type = $item['type'];
        $key = $item['key'];
        $name = implode('.', [...$propertyNames, $key]);

        switch ($type) {
            case 'deleted':
                $line = "Property '{$name}' was removed";
                break;
            case 'unchanged':
                $line = "";
                break;
            case 'added':
                $value = toString($item['value']);
                $line = "Property '{$name}' was added with value: {$value}";
                break;
            case 'changed':
                $oldValue = toString($item['oldValue']);
                $newValue = toString($item['newValue']);
                $line = "Property '{$name}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'parent':
                return format($item['children'], [...$propertyNames, $key]);
            default:
                throw new Exception("Unknown type of item '{$name}'");
        }
        return $line;
    }, $tree);
    return implode("\n", array_values(array_filter($lines)));
}
