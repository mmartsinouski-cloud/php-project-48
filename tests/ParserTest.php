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
        $this->fixturesDir = __DIR__ . '/fixtures';
    }

    public function testParseValidJson()
    {
        $file = $this->fixturesDir . '/file1.json';
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
        file_put_contents($invalidFile, '{invalid json}');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid JSON');

        Parser::parse($invalidFile);

        // Clean up
        unlink($invalidFile);
    }
}
