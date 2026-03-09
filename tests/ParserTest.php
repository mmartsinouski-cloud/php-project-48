<?php

namespace Hexlet\Code\Tests;

use Hexlet\Code\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixturesDir = __DIR__ . '/fixtures/flat';
    }

    public function testParseValidJson()
    {
        $file = $this->fixturesDir . '/file1.json';
        $this->assertFileExists($file, "JSON file not found: {$file}");

        $result = Parser::parse($file);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('host', $result);
        $this->assertEquals('hexlet.io', $result['host']);
        $this->assertEquals(50, $result['timeout']);
        $this->assertArrayHasKey('proxy', $result);
        $this->assertArrayHasKey('follow', $result);
    }

    public function testParseValidYaml()
    {
        $file = $this->fixturesDir . '/file1.yml';
        $this->assertFileExists($file, "YAML file not found: {$file}");

        $result = Parser::parse($file);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('host', $result);
        $this->assertEquals('hexlet.io', $result['host']);
        $this->assertEquals(50, $result['timeout']);
    }

    public function testParseFileNotFound()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File not found: nonexistent.json');

        Parser::parse('nonexistent.json');
    }

    public function testParseInvalidJson()
    {
        $invalidFile = $this->fixturesDir . '/invalid.json';

        // Создаем временный файл с некорректным JSON
        file_put_contents($invalidFile, '{invalid json}');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid JSON');

        try {
            Parser::parse($invalidFile);
        } finally {
            // Удаляем временный файл в любом случае
            if (file_exists($invalidFile)) {
                unlink($invalidFile);
            }
        }
    }

    public function testParseEmptyJson()
    {
        $file = $this->fixturesDir . '/empty.json';
        $this->assertFileExists($file, "Empty JSON file not found: {$file}");

        $result = Parser::parse($file);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testParseUnsupportedFormat()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unsupported file format: txt');

        // Создаем временный файл с неподдерживаемым расширением
        $invalidFile = $this->fixturesDir . '/test.txt';
        file_put_contents($invalidFile, 'test content');

        try {
            Parser::parse($invalidFile);
        } finally {
            if (file_exists($invalidFile)) {
                unlink($invalidFile);
            }
        }
    }
}
