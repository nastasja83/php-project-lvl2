<?php

namespace Differ\Differ;

use Exception;

use function Differ\Differ\formatDiff;
use function Functional\sort;

/**
 * @param object $obj1
 * @param object $obj2
 *
 * @return array
 */
function getDifferTree(object $obj1, object $obj2): array
{
    $mergedKeys = array_keys(array_merge((array) $obj1, (array) $obj2));
    $sortedKeys = sort($mergedKeys, fn ($left, $right) => strcmp($left, $right));

    return array_map(function ($key) use ($obj1, $obj2) {
        if (!property_exists($obj1, $key)) {
            return [
                'key' => $key,
                'value' => $obj2->{$key},
                'type' => 'added'
            ];
        }
        if (!property_exists($obj2, $key)) {
            return [
                'key' => $key,
                'value' => $obj1->{$key},
                'type' => 'deleted'
            ];
        }
        if ($obj1->{$key} === $obj2->{$key}) {
            return [
                'key' => $key,
                'value' => $obj1->{$key},
                'type' => 'unchanged'
            ];
        }
        if (is_object($obj1->{$key}) && is_object($obj2->{$key})) {
            return [
                'key' => $key,
                'type' => 'parent',
                'children' => getDifferTree($obj1->{$key}, $obj2->{$key})
            ];
        }
        return [
            'key' => $key,
            'oldValue' => $obj1->{$key},
            'newValue' => $obj2->{$key},
            'type' => 'changed'
        ];
    }, $sortedKeys);
}

/**
 * @param string $pathToFile
 *
 * @return string
 */
function getContent(string $pathToFile): string
{
    $data = file_get_contents($pathToFile);
    if (is_string($data)) {
        return $data;
    } else {
        throw new Exception('File cannot be read ' . $pathToFile);
    }
}

/**
 * @param string $pathToFile
 *
 * @return object
 */
function getFileData(string $pathToFile): object
{
    $content = getContent($pathToFile);
    $type = pathinfo($pathToFile, PATHINFO_EXTENSION);

    return parse($content, $type);
}

/**
 * @param string $pathToFile1
 * @param string $pathToFile2
 * @param string $format
 *
 * @return string
 */
function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $config1 = getFileData($pathToFile1);
    $config2 = getFileData($pathToFile2);
    $differTree = getDifferTree($config1, $config2);

    return formatDiff($format, $differTree);
}
