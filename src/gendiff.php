<?php

namespace Differ\Differ;

function genDiff($pathToFile1, $pathToFile2)
{
    $config1 = jsonParse(getContent($pathToFile1));
    $config2 = jsonParse(getContent($pathToFile2));

    $mergedConfigs = array_merge($config1, $config2);
    $mergedKeys = array_keys($mergedConfigs);
    sort($mergedKeys);

    $tree = array_map(function ($key) use ($config1, $config2) {
        if (!array_key_exists($key, $config1)) {
            return ['key' => $key,
                    'value' => $config2[$key],
                    'type' => 'added'];
        }
        if (!array_key_exists($key, $config2)) {
            return ['key' => $key,
                    'value' => $config1[$key],
                    'type' => 'deleted'];
        }
        if ($config1[$key] === $config2[$key]) {
            return ['key' => $key,
                    'value' => $config1[$key],
                    'type' => 'unchanged'];
        }
        if ($config1[$key] !== $config2[$key]) {
            return ['key' => $key,
                    'oldValue' => $config1[$key],
                    'newValue' => $config2[$key],
                    'type' => 'changed'];
        }
    }, $mergedKeys);

    return ($tree);
}

function getContent($pathToFile)
{
    $fullPath = realpath($pathToFile);
    return file_get_contents($fullPath);
}
