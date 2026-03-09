<?php

namespace Hexlet\Code\Formatters;

class Json
{
    /**
     * Форматирует AST дерево в JSON формат
     *
     * @param array $ast AST дерево
     * @return string JSON строка
     */
    public static function format(array $ast): string
    {
        $result = json_encode($ast, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($result === false) {
            throw new \RuntimeException('Ошибка кодирования JSON: ' . json_last_error_msg());
        }

        return $result;
    }
}
