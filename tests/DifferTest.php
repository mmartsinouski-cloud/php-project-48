<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\genDiff;

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
        $file2 = $this->fixturesDir . '/file2.json';

        // Проверяем, что файл существует
        $this->assertFileExists($emptyFile, "Empty file not found: {$emptyFile}");

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
    }

    public function testGenDiffWithFileNotFound()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File not found: nonexistent.json');

        genDiff('nonexistent.json', $this->fixturesDir . '/file1.json');
    }

    public function testGenDiffWithInvalidJson()
    {
        $invalidFile = $this->fixturesDir . '/invalid.json';

        // Создаем временный файл с некорректным JSON
        file_put_contents($invalidFile, '{invalid json}');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid JSON');

        try {
            genDiff($invalidFile, $this->fixturesDir . '/file1.json');
        } finally {
            // Удаляем временный файл в любом случае
            if (file_exists($invalidFile)) {
                unlink($invalidFile);
            }
        }
    }

    public function testGenDiffWithYamlFiles()
    {
        $file1 = $this->fixturesDir . '/file1.yml';
        $file2 = $this->fixturesDir . '/file2.yml';

        $this->assertFileExists($file1, "YAML file not found: {$file1}");
        $this->assertFileExists($file2, "YAML file not found: {$file2}");

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

        $this->assertFileExists($file1, "JSON file not found: {$file1}");
        $this->assertFileExists($file2, "YAML file not found: {$file2}");

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
