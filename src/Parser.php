<?php

namespace Hexlet\Code;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * Читает и парсит файлы различных форматов.
 *
 * JSON, YAML
 */
class Parser
{
    /**
     * Читает файл по указанному пути и парсит его содержимое.
     *
     * @param string $filepath
     * @return array Ассоциативный массив с данными
     * @throws Exception
     */
    public static function parse(string $filepath): array
    {
        if (!file_exists($filepath)) {
            throw new Exception("File not found: $filepath");
        }

        $content = file_get_contents($filepath);
        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        return self::parseContent($content, $extension);
    }

    /**
     * Логика парсинга в зависимости от формата
     *
     * @param string $content
     * @param string $extension Расширение файла
     * @return array
     * @throws Exception
     */
    private static function parseContent(string $content, string $extension): array
    {
        switch ($extension) {
            case 'json':
                $data = json_decode($content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception("Invalid JSON: " . json_last_error_msg());
                }
                return $data;

            case 'yml':
            case 'yaml':
                return Yaml::parse($content);

            default:
                throw new Exception("Unsupported file format: $extension");
        }
    }
}
