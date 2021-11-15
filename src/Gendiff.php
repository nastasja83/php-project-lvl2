<?php

namespace Differ\Differ;

use Exception;
use stdClass;

use function Differ\Differ\formatDiff;

/**
 * @param object $config1
 * @param object $config2
 *
 * @return array
 */
function getDifferTree(object $config1, object $config2): array
{
    $mergedKeys = array_keys(array_merge((array) $config1, (array) $config2));
    sort($mergedKeys);

    return array_map(function ($key) use ($config1, $config2) {
        if (!property_exists($config1, $key)) {
            return ['key' => $key,
                    'value' => $config2->{$key},
                    'type' => 'added'];
        }
        if (!property_exists($config2, $key)) {
            return ['key' => $key,
                    'value' => $config1->{$key},
                    'type' => 'deleted'];
        }
        if ($config1->{$key} === $config2->{$key}) {
            return ['key' => $key,
                    'value' => $config1->{$key},
                    'type' => 'unchanged'];
        }
        if ($config1->{$key} !== $config2->{$key}) {
            return ['key' => $key,
                    'oldValue' => $config1->{$key},
                    'newValue' => $config2->{$key},
                    'type' => 'changed'];
        }
    }, $mergedKeys);
}

function getContent(string $pathToFile): string
{
    $data = file_get_contents($pathToFile);
    if (is_string($data)) {
        return $data;
    } else {
        throw new Exception('File cannot be read ' . $pathToFile);
    }
}

function getFileData(string $pathToFile): object
{
    $content = getContent($pathToFile);
    $type = pathinfo($pathToFile, PATHINFO_EXTENSION);

    return parse($content, $type);
}

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $config1 = getFileData($pathToFile1);
    $config2 = getFileData($pathToFile2);
    $differTree = getDifferTree($config1, $config2);

    return formatDiff($format, $differTree);
}
