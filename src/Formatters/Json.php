<?php

namespace Hexlet\Code\Formatters;

/**
 * Форматирует AST дерево в JSON формат.
 */
class Json
{
    /**
     * Преобразует AST дерево в JSON строку.
     *
     * @param array $ast
     * @return string JSON строка с отступами
     */
    public static function format(array $ast): string
    {
        return json_encode($ast, JSON_PRETTY_PRINT);
    }
}
