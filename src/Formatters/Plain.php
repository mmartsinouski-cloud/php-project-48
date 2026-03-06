<?php

namespace Hexlet\Code\Formatters;

/**
 * Форматирует AST дерево в plain формат.
 *
 */
class Plain
{
    /**
     * Преобразует AST дерево в plain текстовый формат.
     *
     * @param array $ast AST дерево различий
     * @return string Текстовое представление изменений
     *
     */
    public static function format(array $ast): string
    {
        $lines = self::iter($ast, '');
        return implode("\n", $lines);
    }

    /**
     * Рекурсивно обходит AST и собирает строки описаний.
     *
     * @param array $ast Текущий узел AST
     * @param string $path Путь к текущему узлу (для вложенных)
     * @return array
     *
     */
    private static function iter(array $ast, string $path): array
    {
        $lines = [];

        foreach ($ast as $node) {
            $key = $node['key'];
            $currentPath = $path ? "$path.$key" : $key;

            switch ($node['type']) {
                case 'nested':
                    $lines = array_merge($lines, self::iter($node['children'], $currentPath));
                    break;

                case 'added':
                    $value = self::stringify($node['value']);
                    $lines[] = "Property '$currentPath' was added with value: $value";
                    break;

                case 'removed':
                    $lines[] = "Property '$currentPath' was removed";
                    break;

                case 'changed':
                    $oldValue = self::stringify($node['oldValue']);
                    $newValue = self::stringify($node['newValue']);
                    $lines[] = "Property '$currentPath' was updated. From $oldValue to $newValue";
                    break;

                case 'unchanged':
                    break;

                default:
                    throw new \Exception("Unknown node type: {$node['type']}");
            }
        }

        return $lines;
    }

    /**
     * Преобразует значение в строковое представление для plain формата.
     *
     * @param mixed $value Значение для преобразования
     * @return string Строковое представление:
     *                - [complex value] для массивов
     *                - true/false для булевых
     *                - null для null
     *                - 'строка' для строк
     *                - число для числовых значений
     */
    private static function stringify(mixed $value): string
    {
        if (is_array($value)) {
            return '[complex value]';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_null($value)) {
            return 'null';
        }

        if (is_string($value)) {
            return "'$value'";
        }

        return (string)$value;
    }
}
