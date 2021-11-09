<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    private string $path = __DIR__ . "/fixtures/";

    private function getFilePath(string $name): string
    {
        return $this->path . $name;
    }

    public function testGendiffPlain(): void
    {
        $expectedPlain = file_get_contents($this->getFilePath('plain.txt'));
        $firstPath = $this->getFilePath('first.json');
        $secondPath = $this->getFilePath('second.json');
        $this->assertEquals($expectedPlain, genDiff($firstPath, $secondPath));
    }
}
