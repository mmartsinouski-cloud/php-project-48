<?php

namespace Hexlet\Code\Formatters;

class Stylish
{
    private const INDENT_SIZE = 4;
    private const OFFSET = 2;

    public static function format(array $ast): string
    {
        $result = self::iter($ast, 1);
        return "{\n" . $result . '}';
    }

    private static function iter(array $ast, int $depth): string
    {
        $lines = [];
        $currentIndent = str_repeat(' ', $depth * self::INDENT_SIZE - self::OFFSET);
        $bracketIndent = str_repeat(' ', ($depth - 1) * self::INDENT_SIZE);

        foreach ($ast as $node) {
            $type = $node['type'];
            $key = $node['key'];

            switch ($type) {
                case 'nested':
                    $children = self::iter($node['children'], $depth + 1);
                    $lines[] = "{$currentIndent}  {$key}: {\n{$children}{$bracketIndent}  }";
                    break;

                case 'unchanged':
                    $lines[] = "{$currentIndent}  {$key}: " . self::stringify($node['value'], $depth + 1);
                    break;

                case 'changed':
                    $lines[] = "{$currentIndent}- {$key}: " . self::stringify($node['oldValue'], $depth + 1);
                    $lines[] = "{$currentIndent}+ {$key}: " . self::stringify($node['newValue'], $depth + 1);
                    break;

                case 'added':
                    $lines[] = "{$currentIndent}+ {$key}: " . self::stringify($node['value'], $depth + 1);
                    break;

                case 'removed':
                    $lines[] = "{$currentIndent}- {$key}: " . self::stringify($node['value'], $depth + 1);
                    break;
            }
        }

        return implode("\n", $lines) . "\n";
    }

    private static function stringify($value, int $depth): string
    {
        if ($value === null) {
            return 'null';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (!is_array($value)) {
            return (string)$value;
        }

        // Для пустого массива
        if (empty($value)) {
            return '{}';
        }

        // Проверяем, ассоциативный ли массив
        if (self::isAssoc($value)) {
            return self::stringifyAssocArray($value, $depth);
        }

        // Индексированный массив
        return self::stringifyIndexedArray($value, $depth);
    }

    private static function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private static function stringifyAssocArray(array $array, int $depth): string
    {
        $indent = str_repeat(' ', $depth * self::INDENT_SIZE);
        $bracketIndent = str_repeat(' ', ($depth - 1) * self::INDENT_SIZE);

        $lines = [];
        foreach ($array as $key => $value) {
            $lines[] = $indent . $key . ': ' . self::stringify($value, $depth + 1);
        }

        return "{\n" . implode("\n", $lines) . "\n" . $bracketIndent . '}';
    }

    private static function stringifyIndexedArray(array $array, int $depth): string
    {
        $indent = str_repeat(' ', $depth * self::INDENT_SIZE);
        $bracketIndent = str_repeat(' ', ($depth - 1) * self::INDENT_SIZE);

        $lines = [];
        foreach ($array as $value) {
            $lines[] = $indent . self::stringify($value, $depth + 1);
        }

        return "[\n" . implode("\n", $lines) . "\n" . $bracketIndent . ']';
    }
}
