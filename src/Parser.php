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
                return $data;

            case 'yml':
            case 'yaml':
                return Yaml::parse($content);

            default:
                throw new \Exception("Unsupported file format: {$extension}");
        }
    }
}
