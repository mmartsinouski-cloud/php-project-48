<?php

require_once __DIR__ . '/vendor/autoload.php';

use function Hexlet\Code\genDiff;

$diff = genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json');
echo $diff . "\n";
