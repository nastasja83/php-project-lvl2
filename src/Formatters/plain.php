<?php

namespace Differ\Differ\Formatters;

function toString($value): string
{
     return trim(var_export($value, true), "'");
}

function format($tree): string
{
    $lines = array_map(function ($item) {
        $key = $item['key'];
        $type = $item['type'];

        switch($type) {
            case 'deleted':
                $value = $item['value'];
                return "- {$key}: {$value}";
            case 'unchanged':
                $value = $item['value'];
                return "  {$key}: {$value}";
            case 'added':
                $value = $item['value'];
                return "+ {$key}: {$value}";
            case 'changed':
                $oldValue = $item['oldValue'];
                $newValue = $item['newValue'];
                return "- {$key}: {$oldValue}\n+ {$key}: {$newValue}";
        }
    }, $tree);
    $result = implode("\n", $lines);
    return "\n{$result}\n";
}