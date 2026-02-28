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
        $this->fixturesDir = __DIR__ . '/fixtures';
    }

    public function testGenDiffWithIdenticalFiles()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file1.json'; // Одинаковые файлы

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

        // Ключи в алфавитном порядке: follow, host, proxy, timeout, verbose
        $expected = implode("\n", [
            '{',
            '  - follow: false',
            '    host: hexlet.io',
            '  - proxy: 123.234.53.22',
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

        // Ключи должны быть в АЛФАВИТНОМ ПОРЯДКЕ: host, timeout, verbose
        $expected = implode("\n", [
            '{',
            '  + host: hexlet.io',
            '  + timeout: 20',
            '  + verbose: true',
            '}'
        ]);

        $this->assertEquals($expected, genDiff($emptyFile, $file2));

        // Clean up
        unlink($emptyFile);
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
        file_put_contents($invalidFile, '{invalid json}');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid JSON');

        genDiff($invalidFile, $this->fixturesDir . '/file1.json');

        // Clean up
        unlink($invalidFile);
    }
}
