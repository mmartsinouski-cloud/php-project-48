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

    public function testGenDiffWithRecursiveJson()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file2.json';

        $expected = file_get_contents($this->fixturesDir . '/expected_stylish.txt');
        $expected = trim($expected);

        $actual = genDiff($file1, $file2);
        $actual = trim($actual);

        $this->assertEquals($expected, $actual);
    }

    public function testGenDiffWithRecursiveYaml()
    {
        $file1 = $this->fixturesDir . '/file1.yml';
        $file2 = $this->fixturesDir . '/file2.yml';

        $expected = file_get_contents($this->fixturesDir . '/expected_stylish.txt');
        $expected = trim($expected);

        $actual = genDiff($file1, $file2);
        $actual = trim($actual);

        $this->assertEquals($expected, $actual);
    }

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
