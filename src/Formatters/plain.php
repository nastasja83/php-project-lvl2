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

        switch ($type) {
            case 'deleted':
                $value = $item['value'];
                $line = "- {$key}: {$value}";
                break;
            case 'unchanged':
                $value = $item['value'];
                $line = "  {$key}: {$value}";
                break;
            case 'added':
                $value = $item['value'];
                $line = "+ {$key}: {$value}";
                break;
            case 'changed':
                $oldValue = $item['oldValue'];
                $newValue = $item['newValue'];
                $line = "- {$key}: {$oldValue}\n+ {$key}: {$newValue}";
                break;
        }
        return $line;
    }, $tree);
    $unitedLines = implode("\n", $lines);
    $result =  "\n{$unitedLines}\n";
    return $result;
}
