<?php

namespace Differ\Differ;

use Exception;

use function Differ\Differ\Formatters\toString;
use function Differ\Differ\Formatters\format;

/**
 * Get differTree array

 * @param array<mixed> $config1 array
 * @param array<mixed> $config2 array

 * @return array<mixed>
 */
function getDifferTree(array $config1, array $config2): array
{
    $mergedKeys = array_keys(array_merge($config1, $config2));
    sort($mergedKeys);

    return array_map(function ($key) use ($config1, $config2) {
        if (!array_key_exists($key, $config1)) {
            return ['key' => $key,
                    'value' => toString($config2[$key]),
                    'type' => 'added'];
        }
        if (!array_key_exists($key, $config2)) {
            return ['key' => $key,
                    'value' => toString($config1[$key]),
                    'type' => 'deleted'];
        }
        if ($config1[$key] === $config2[$key]) {
            return ['key' => $key,
                    'value' => toString($config1[$key]),
                    'type' => 'unchanged'];
        }
        if ($config1[$key] !== $config2[$key]) {
            return ['key' => $key,
                    'oldValue' => toString($config1[$key]),
                    'newValue' => toString($config2[$key]),
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

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $config1 = jsonParse(getContent($pathToFile1));
    $config2 = jsonParse(getContent($pathToFile2));
    $differTree = getDifferTree($config1, $config2);

    return format($differTree);
}
