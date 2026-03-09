<?php

namespace Hexlet\Code;

use Symfony\Component\Yaml\Yaml;

class Parser
{
    /**
     * Парсит файл JSON или YAML в ассоциативный массив
     *
     * @param string $filepath
     * @return array Ассоциативный массив
     */
    public static function parse($filepath): array
    {
        if (!file_exists($filepath)) {
            throw new \Exception("File not found: {$filepath}");
        }

        $content = file_get_contents($filepath);

        if ($content === false) {
            throw new \RuntimeException("Не удалось прочитать файл: {$filepath}");
        }

        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        return self::parseContent($content, $extension);
    }

    /**
     * Парсит содержимое файла в зависимости от его формата
     *
     * @param string $content
     * @param string $extension
     * @return array Ассоциативный массив
     */
    private static function parseContent(string $content, string $extension): array
    {
        switch ($extension) {
            case 'json':
                $data = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid JSON: " . json_last_error_msg());
                }
                if (!is_array($data)) {
                    throw new \Exception("JSON должен содержать объект верхнего уровня");
                }
                return $data;

            case 'yml':
            case 'yaml':
                $data = Yaml::parse($content);
                // Добавляем проверку, что YAML распарсился в массив
                if (!is_array($data)) {
                    throw new \Exception("YAML должен содержать массив верхнего уровня");
                }
                return $data;

            default:
                throw new \Exception("Unsupported file format: {$extension}");
        }
    }
}
