<?php

namespace Differ\Differ;

use function Differ\Differ\Formatters\toString;
use function Differ\Differ\Formatters\format;

function genDiff($pathToFile1, $pathToFile2)
{
    $config1 = jsonParse(getContent($pathToFile1));
    $config2 = jsonParse(getContent($pathToFile2));

    $mergedKeys = array_keys(array_merge($config1, $config2));
    sort($mergedKeys);

    $tree = array_map(function ($key) use ($config1, $config2) {
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
    return format($tree);
}

function getContent($pathToFile)
{
    $fullPath = realpath($pathToFile);
    return file_get_contents($fullPath);
}
