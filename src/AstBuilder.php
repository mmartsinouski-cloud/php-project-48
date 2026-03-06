<?php

namespace Hexlet\Code;

/**
 * Строит AST различий между двумя структурами данных.
 *
 * Каждый узел дерева содержит ключ, тип изменения и соответствующие значения.
 * Типы узлов: nested (вложенный), unchanged (без изменений), changed (изменен),
 * added (добавлен), removed (удален).
 */
class AstBuilder
{
    /**
     * Строит AST дерево различий между двумя массивами
     *
     * @param array $data1
     * @param array $data2
     * @return array
     */
    public static function build(array $data1, array $data2): array
    {
        $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
        sort($keys);

        return array_map(function ($key) use ($data1, $data2) {
            $value1 = $data1[$key] ?? null;
            $value2 = $data2[$key] ?? null;

            $exists1 = array_key_exists($key, $data1);
            $exists2 = array_key_exists($key, $data2);

            // Если оба значения - массивы и ассоциативные, идем вглубь
            if (
                $exists1 && $exists2 &&
                is_array($value1) && is_array($value2) &&
                self::isAssoc($value1) && self::isAssoc($value2)
            ) {
                return [
                    'key' => $key,
                    'type' => 'nested',
                    'children' => self::build($value1, $value2)
                ];
            }

            // Ключ есть в обоих файлах
            if ($exists1 && $exists2) {
                if ($value1 === $value2) {
                    return [
                        'key' => $key,
                        'type' => 'unchanged',
                        'value' => $value1
                    ];
                }

                return [
                    'key' => $key,
                    'type' => 'changed',
                    'oldValue' => $value1,
                    'newValue' => $value2
                ];
            }

            // Ключ только в первом файле
            if ($exists1) {
                return [
                    'key' => $key,
                    'type' => 'removed',
                    'value' => $value1
                ];
            }

            // Ключ только во втором файле
            return [
                'key' => $key,
                'type' => 'added',
                'value' => $value2
            ];
        }, $keys);
    }

    /**
     * Проверяет, является ли массив ассоциативным
     *
     * @param array $array
     * @return bool
     */
    private static function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
