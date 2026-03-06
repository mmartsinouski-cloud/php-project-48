<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Hexlet\Code\genDiff;

/**
 * Тесты для plain форматера.
 *
 */
class DifferPlainTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixturesDir = __DIR__ . '/fixtures/recursive';
    }

    private function normalizeLineEndings(string $str): string
    {
        return str_replace("\r\n", "\n", trim($str));
    }

    public function testGenDiffPlainWithRecursiveJson()
    {
        $file1 = $this->fixturesDir . '/file1.json';
        $file2 = $this->fixturesDir . '/file2.json';

        $expected = implode("\n", [
            "Property 'common.follow' was added with value: false",
            "Property 'common.setting2' was removed",
            "Property 'common.setting3' was updated. From true to null",
            "Property 'common.setting4' was added with value: 'blah blah'",
            "Property 'common.setting5' was added with value: [complex value]",
            "Property 'common.setting6.doge.wow' was updated. From '' to 'so much'",
            "Property 'common.setting6.ops' was added with value: 'vops'",
            "Property 'group1.baz' was updated. From 'bas' to 'bars'",
            "Property 'group1.nest' was updated. From [complex value] to 'str'",
            "Property 'group2' was removed",
            "Property 'group3' was added with value: [complex value]",
        ]);

        $actual = genDiff($file1, $file2, 'plain');

        $this->assertEquals(
            $this->normalizeLineEndings($expected),
            $this->normalizeLineEndings($actual)
        );
    }

    public function testGenDiffPlainWithRecursiveYaml()
    {
        $file1 = $this->fixturesDir . '/file1.yml';
        $file2 = $this->fixturesDir . '/file2.yml';

        $expected = implode("\n", [
            "Property 'common.follow' was added with value: false",
            "Property 'common.setting2' was removed",
            "Property 'common.setting3' was updated. From true to null",
            "Property 'common.setting4' was added with value: 'blah blah'",
            "Property 'common.setting5' was added with value: [complex value]",
            "Property 'common.setting6.doge.wow' was updated. From '' to 'so much'",
            "Property 'common.setting6.ops' was added with value: 'vops'",
            "Property 'group1.baz' was updated. From 'bas' to 'bars'",
            "Property 'group1.nest' was updated. From [complex value] to 'str'",
            "Property 'group2' was removed",
            "Property 'group3' was added with value: [complex value]",
        ]);

        $actual = genDiff($file1, $file2, 'plain');

        $this->assertEquals(
            $this->normalizeLineEndings($expected),
            $this->normalizeLineEndings($actual)
        );
    }

    public function testGenDiffPlainWithFlatFiles()
    {
        $flatDir = __DIR__ . '/fixtures/flat';
        $file1 = $flatDir . '/file1.json';
        $file2 = $flatDir . '/file2.json';

        $expected = implode("\n", [
            "Property 'timeout' was updated. From 50 to 20",
            "Property 'verbose' was added with value: true",
        ]);

        // Сортируем строки для сравнения, так как порядок может отличаться
        $actual = genDiff($file1, $file2, 'plain');
        $actualLines = explode("\n", trim($actual));
        sort($actualLines);

        $expectedLines = explode("\n", $expected);
        sort($expectedLines);

        $this->assertEquals($expectedLines, $actualLines);
    }

    public function testGenDiffPlainWithEmptyFile()
    {
        $flatDir = __DIR__ . '/fixtures/flat';
        $emptyFile = $flatDir . '/empty.json';
        $file2 = $flatDir . '/file2.json';

        $expected = implode("\n", [
            "Property 'follow' was added with value: false",
            "Property 'host' was added with value: 'hexlet.io'",
            "Property 'proxy' was added with value: '123.234.53.22'",
            "Property 'timeout' was added with value: 20",
            "Property 'verbose' was added with value: true",
        ]);

        $actual = genDiff($emptyFile, $file2, 'plain');
        $actualLines = explode("\n", trim($actual));
        sort($actualLines);

        $expectedLines = explode("\n", $expected);
        sort($expectedLines);

        $this->assertEquals($expectedLines, $actualLines);
    }
}
