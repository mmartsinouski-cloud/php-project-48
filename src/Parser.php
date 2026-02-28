<?php

namespace Hexlet\Code;

use Exception;

class Parser
{

    /**
     * Читает и парсит файл в зависимости от расширения
     *
     * @throws Exception Если файл не найден или формат не поддерживается
     */
    public static function parse(string $filepath): array
    {
        if (!file_exists($filepath)) {
            throw new Exception("File not found: $filepath");
        }

        $content = file_get_contents($filepath);
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        return match ($extension) {
            'json' => self::parseJson($content),
            default => throw new Exception("Unsupported file extension: $extension"),
        };
    }

    /**
     *
     * Парсит JSON содержимое
     * @throws Exception
     */
    private static function parseJson(string $content): array
    {
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON: " . json_last_error_msg());
        }

        return $data;
    }

    /**
     *
     * Парсит содержимое файла и возвращает массив ключей и значений
     * @throws Exception
     */
    public static function parseToArray(string $filepath): array
    {
        return self::parse($filepath);
    }
}