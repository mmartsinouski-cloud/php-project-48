<?php

namespace  Hexlet\Code;

use function Funct\Collection\sortBy;

function genDiff(string $path1, string $path2): string
{
    $data1 = Parser::parseToArray($path1);
    $data2 = Parser::parseToArray($path2);

    $allKeys = array_unique(array_merge(array_keys($data1), array_keys($data2)));

    $sortedKeys = sortBy($allKeys, function ($key) {
        return $key;
    });

    $lines = array_reduce($sortedKeys, function ($acc, $key) use ($data1, $data2) {
        $exists1 = array_key_exists($key, $data1);
        $exists2 = array_key_exists($key, $data2);
        $value1 = $exists1 ? formatValue($data1[$key]) : null;
        $value2 = $exists2 ? formatValue($data2[$key]) : null;

        if($exists1 && $exists2 && $data1[$key] === $data2[$key]) {
            $acc[] = "    {$key}: {$value1}";
        } elseif ($exists1 && $exists2 && $data1[$key] !== $data2[$key]) {
            $acc[] = "  - {$key}: {$value1}";
            $acc[] = "  + {$key}: {$value2}";
        } elseif ($exists1 && !$exists2) {
            $acc[] = "  - {$key}: {$value1}";
        } elseif ($exists2 && !$exists1) {
            $acc[] = "  + {$key}: {$value2}";
        }

        return $acc;
    }, []);

    return "{\n" . implode("\n", $lines) . "\n}";
}

function formatValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    return $value;
}
