<?php

namespace Hexlet\Code;

use Exception;

class Parser
{
    /**
     * Парсит JSON файл и возвращает ассоциативный массив
     *
     * @throws Exception Если файл не найден или JSON невалидный
     */
    public static function parseToArray($filepath)
    {
        $realPath = realpath($filepath);

        if ($realPath === false) {
            throw new \Exception("File not found: {$filepath}");
        }

        $content = file_get_contents($realPath);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid JSON: " . json_last_error_msg());
        }

        return $data;
    }
}
