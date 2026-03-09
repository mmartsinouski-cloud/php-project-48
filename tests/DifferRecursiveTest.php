<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\genDiff;

class DifferRecursiveTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixturesDir = __DIR__ . '/fixtures/recursive';
    }

    /**
     * Тест проверяет только один сценарий - сравнение JSON и YAML файлов
     * Остальные сценарии уже покрыты в DifferTest (плоские файлы)
     * и в DifferPlainTest/DifferJsonTest (разные форматы вывода)
     */
    public function testGenDiffWithMixedRecursiveFormats()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file2.yml';

        $expected = file_get_contents($this->fixturesDir . '/expected_stylish.txt');
        $expected = trim($expected);

        $actual = genDiff($file1, $file2);
        $actual = trim($actual);

        $this->assertEquals($expected, $actual);
    }
}
