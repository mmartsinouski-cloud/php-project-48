<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\genDiff;

/**
 * Тесты для JSON форматера.
 *
 */
class DifferJsonTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixturesDir = __DIR__ . '/fixtures/recursive';
    }

    public function testGenDiffJsonWithRecursiveJson()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file2.json';

        $actual = genDiff($file1, $file2, 'json');

        // Проверяем, что вывод - валидный JSON
        $this->assertJson($actual);

        // Декодируем и проверяем структуру
        $data = json_decode($actual, true);
        $this->assertIsArray($data);

        // Проверяем наличие ключей
        $keys = array_column($data, 'key');
        $this->assertContains('common', $keys);
        $this->assertContains('group1', $keys);
        $this->assertContains('group2', $keys);
        $this->assertContains('group3', $keys);
    }

    public function testGenDiffJsonWithRecursiveYaml()
    {
        $file1 = $this->fixturesDir . '/file1.yml';
        $file2 = $this->fixturesDir . '/file2.yml';

        $actual = genDiff($file1, $file2, 'json');

        $this->assertJson($actual);
        $data = json_decode($actual, true);
        $this->assertIsArray($data);
    }

    public function testGenDiffJsonWithMixedFormats()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file2.yml';

        $actual = genDiff($file1, $file2, 'json');

        $this->assertJson($actual);
        $data = json_decode($actual, true);
        $this->assertIsArray($data);
    }

    public function testGenDiffJsonWithFlatFiles()
    {
        $flatDir = __DIR__ . '/fixtures/flat';
        $file1 = $flatDir . '/file1.json';
        $file2 = $flatDir . '/file2.json';

        $actual = genDiff($file1, $file2, 'json');

        $this->assertJson($actual);
        $data = json_decode($actual, true);
        $this->assertIsArray($data);

        // Проверяем конкретные узлы
        $keys = array_column($data, 'key');
        $this->assertContains('timeout', $keys);
        $this->assertContains('verbose', $keys);
    }

    public function testGenDiffJsonWithEmptyFile()
    {
        $flatDir = __DIR__ . '/fixtures/flat';
        $emptyFile = $flatDir . '/empty.json';
        $file2 = $flatDir . '/file2.json';

        $actual = genDiff($emptyFile, $file2, 'json');

        $this->assertJson($actual);
        $data = json_decode($actual, true);
        $this->assertIsArray($data);

        // Все ключи из file2 должны быть со статусом 'added'
        foreach ($data as $node) {
            $this->assertEquals('added', $node['type']);
        }
    }
}
