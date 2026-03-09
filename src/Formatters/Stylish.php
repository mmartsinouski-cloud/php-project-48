<?php

namespace Hexlet\Code\Formatters;

class Stylish
{
    private const int INDENT_SIZE = 4;

    /**
     * Форматирует AST дерево в stylish формат
     *
     * @param array $ast AST дерево
     * @return string Отформатированный stylish текст
     */
    public static function format(array $ast): string
    {
        return "{\n" . self::iter($ast, 0) . "\n}";
    }

    /**
     * Рекурсивно обходит AST дерево и формирует строки stylish формата
     *
     * @param array $ast Текущий узел AST дерева
     * @param int $depth Текущая глубина вложенности
     * @return string Отформатированный текст для текущего уровня
     */
    private static function iter(array $ast, int $depth): string
    {
        $indentSize = ($depth + 1) * self::INDENT_SIZE;
        $mainIndent = str_repeat(' ', $indentSize); // 4, 8, 12...
        $signIndent = str_repeat(' ', $indentSize - 2); // 2, 6, 10...

        $lines = array_map(function ($node) use ($depth, $mainIndent, $signIndent) {
            $key = $node['key'];

            switch ($node['type']) {
                case 'nested':
                    $children = self::iter($node['children'], $depth + 1);
                    return "{$mainIndent}{$key}: {\n{$children}\n{$mainIndent}}";

                case 'unchanged':
                    return "{$mainIndent}{$key}: " . self::stringify($node['value'], $depth + 1);

                case 'added':
                    return "{$signIndent}+ {$key}: " . self::stringify($node['value'], $depth + 1);

                case 'removed':
                    return "{$signIndent}- {$key}: " . self::stringify($node['value'], $depth + 1);

                case 'changed':
                    $line1 = "{$signIndent}- {$key}: " . self::stringify($node['oldValue'], $depth + 1);
                    $line2 = "{$signIndent}+ {$key}: " . self::stringify($node['newValue'], $depth + 1);
                    return "{$line1}\n{$line2}";

                default:
                    throw new \Exception("Unknown node type: {$node['type']}");
            }
        }, $ast);

        return implode("\n", $lines);
    }

    /**
     * Преобразует значение в строковое представление для stylish формата
     *
     * @param mixed $value Значение для преобразования
     * @param int $depth Текущая глубина вложенности
     * @return string Строковое представление значения
     */
    private static function stringify(mixed $value, int $depth): string
    {
        if (!is_array($value)) {
            if ($value === null) {
                return 'null';
            }
            if (is_bool($value)) {
                return $value ? 'true' : 'false';
            }
            return (string)$value;
        }

        $indentSize = $depth * self::INDENT_SIZE;
        $currentIndent = str_repeat(' ', $indentSize + self::INDENT_SIZE);
        $bracketIndent = str_repeat(' ', $indentSize);

        $lines = array_map(
            function ($key, $val) use ($depth, $currentIndent) {
            // Добавил $currentIndent в use
                return "{$currentIndent}{$key}: " . self::stringify($val, $depth + 1);
            },
            array_keys($value),
            $value
        );

        return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
    }
}
