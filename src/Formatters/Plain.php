<?php

namespace Hexlet\Code\Formatters;

class Plain
{
    /**
     * Форматирует AST дерево в plain текстовый формат
     *
     * @param array $ast AST дерево
     * @return string Отформатированный plain текст
     */
    public static function format(array $ast): string
    {
        $lines = self::iter($ast, '');
        return implode("\n", $lines);
    }

    /**
     * Рекурсивно обходит AST дерево и формирует строки plain формата
     *
     * @param array $ast Текущий узел AST дерева
     * @param string $path Путь к текущему узлу (для вложенных свойств)
     * @return array Массив строк для вывода
     */
    private static function iter(array $ast, string $path): array
    {
        $lines = [];

        foreach ($ast as $node) {
            $key = $node['key'];
            $currentPath = $path ? "{$path}.{$key}" : $key;

            switch ($node['type']) {
                case 'nested':
                    $lines = array_merge($lines, self::iter($node['children'], $currentPath));
                    break;

                case 'added':
                    $value = self::stringify($node['value']);
                    $lines[] = "Property '{$currentPath}' was added with value: {$value}";
                    break;

                case 'removed':
                    $lines[] = "Property '{$currentPath}' was removed";
                    break;

                case 'changed':
                    $oldValue = self::stringify($node['oldValue']);
                    $newValue = self::stringify($node['newValue']);
                    $lines[] = "Property '{$currentPath}' was updated. From {$oldValue} to {$newValue}";
                    break;

                case 'unchanged':
                    // Ничего не добавляем
                    break;

                default:
                    throw new \Exception("Unknown node type: {$node['type']}");
            }
        }

        return $lines;
    }

    /**
     * Преобразует значение в строковое представление для plain формата
     *
     * @param mixed $value Значение для преобразования
     * @return string Строковое представление значения
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
            return "'{$value}'";
        }

        if (is_numeric($value)) {
            return (string)$value;
        }

        return (string)$value;
    }
}
