<?php

namespace Differ\Differ;

use Exception;

use function Differ\Differ\formatDiff;
use function Functional\sort;

/**
 * @param object $data1
 * @param object $data2
 *
 * @return array
 */
function getDifferTree(object $data1, object $data2): array
{
    $mergedKeys = array_keys(array_merge((array) $data1, (array) $data2));
    $sortedKeys = sort($mergedKeys, fn ($left, $right) => strcmp($left, $right));

    return array_map(function ($key) use ($data1, $data2) {
        if (!property_exists($data1, $key)) {
            return [
                'key' => $key,
                'value' => $data2->{$key},
                'type' => 'added'
            ];
        }
        if (!property_exists($data2, $key)) {
            return [
                'key' => $key,
                'value' => $data1->{$key},
                'type' => 'deleted'
            ];
        }
        if ($data1->{$key} === $data2->{$key}) {
            return [
                'key' => $key,
                'value' => $data1->{$key},
                'type' => 'unchanged'
            ];
        }
        if (is_object($data1->{$key}) && is_object($data2->{$key})) {
            return [
                'key' => $key,
                'type' => 'parent',
                'children' => getDifferTree($data1->{$key}, $data2->{$key})
            ];
        }
        return [
            'key' => $key,
            'oldValue' => $data1->{$key},
            'newValue' => $data2->{$key},
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
