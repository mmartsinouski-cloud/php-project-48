<?php

namespace Differ\Differ;

use Hexlet\Code\Formatters\Stylish;
use Hexlet\Code\Formatters\Plain;
use Hexlet\Code\Formatters\Json;
use Hexlet\Code\AstBuilder;
use Hexlet\Code\Parser;

/**
 * Сравнивает два файла и возвращает разницу в указанном формате
 *
 * @param string $path1
 * @param string $path2
 * @param string $format
 * @return string
 */
function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $data1 = Parser::parse($path1);
    $data2 = Parser::parse($path2);

    $ast = AstBuilder::build($data1, $data2);

    return formatAst($ast, $format);
}

/**
 * Форматирует AST дерево в указанном формате
 *
 * @param array $ast AST дерево
 * @param string $format
 * @return string
 */
function formatAst(array $ast, string $format): string
{
    return match ($format) {
        'stylish' => Stylish::format($ast),
        'plain'   => Plain::format($ast),
        'json'    => Json::format($ast),
        default   => throw new \Exception("Unknown format: {$format}"),
    };
}
