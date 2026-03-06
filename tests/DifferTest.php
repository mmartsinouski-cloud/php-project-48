<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\genDiff;

/**
 * Базовые тесты для функции genDiff с плоскими файлами.
 * Проверяет идентичные файлы, разные файлы, пустые файлы,
 * обработку ошибок и поддержку различных форматов.
 */
class DifferTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixturesDir = __DIR__ . '/fixtures/flat';
    }

    public function testGenDiffWithIdenticalFiles()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file1.json';

        // Сортируем ключи в алфавитном порядке
        $expected = implode("\n", [
            '{',
            '    follow: false',
            '    host: hexlet.io',
            '    proxy: 123.234.53.22',
            '    timeout: 50',
            '}'
        ]);

        $this->assertEquals($expected, genDiff($file1, $file2));
    }

    public function testGenDiffWithDifferentFiles()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file2.json';

        $expected = implode("\n", [
            '{',
            '    follow: false',
            '    host: hexlet.io',
            '    proxy: 123.234.53.22',
            '  - timeout: 50',
            '  + timeout: 20',
            '  + verbose: true',
            '}'
        ]);

        $this->assertEquals($expected, genDiff($file1, $file2));
    }

    public function testGenDiffWithEmptyFile()
    {
        $emptyFile = $this->fixturesDir . '/empty.json';
        file_put_contents($emptyFile, '{}');

        $file2 = $this->fixturesDir . '/file2.json';

        $expected = implode("\n", [
            '{',
            '  + follow: false',
            '  + host: hexlet.io',
            '  + proxy: 123.234.53.22',
            '  + timeout: 20',
            '  + verbose: true',
            '}'
        ]);

        $this->assertEquals($expected, genDiff($emptyFile, $file2));

        unlink($emptyFile);
    }

    public function testGenDiffWithFileNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('File not found: nonexistent.json');

        genDiff('nonexistent.json', $this->fixturesDir . '/file1.json');
    }

    public function testGenDiffWithInvalidJson()
    {
        $invalidFile = $this->fixturesDir . '/invalid.json';
        file_put_contents($invalidFile, '{invalid json}');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid JSON');

        genDiff($invalidFile, $this->fixturesDir . '/file1.json');

        unlink($invalidFile);
    }

    public function testGenDiffWithYamlFiles()
    {
        $file1 = $this->fixturesDir . '/file1.yml';
        $file2 = $this->fixturesDir . '/file2.yml';

        $expected = implode("\n", [
            '{',
            '    follow: false',
            '    host: hexlet.io',
            '    proxy: 123.234.53.22',
            '  - timeout: 50',
            '  + timeout: 20',
            '  + verbose: true',
            '}'
        ]);

        $this->assertEquals($expected, genDiff($file1, $file2));
    }

    public function testGenDiffWithMixedFormats()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file2.yml';

        $expected = implode("\n", [
            '{',
            '    follow: false',
            '    host: hexlet.io',
            '    proxy: 123.234.53.22',
            '  - timeout: 50',
            '  + timeout: 20',
            '  + verbose: true',
            '}'
        ]);

        $this->assertEquals($expected, genDiff($file1, $file2));
    }
}
