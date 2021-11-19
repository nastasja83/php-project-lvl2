<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GendiffTest extends TestCase
{
    private string $path = __DIR__ . "/fixtures/";

    /**
     * @param string $name
     *
     * @return string
     */
    private function getFilePath(string $name): string
    {
        return $this->path . $name;
    }

    /**
     * @return void
     */
    public function testGendiff(): void
    {
        $expectedStylish = file_get_contents($this->getFilePath('StylishExpected.txt'));
        $expectedPlain = file_get_contents($this->getFilePath('PlainExpected.txt'));
        $firstPathJson = $this->getFilePath('first.json');
        $secondPathJson = $this->getFilePath('second.json');
        $firstPathYaml = $this->getFilePath('first.yml');
        $secondPathYaml = $this->getFilePath('second.yml');
        $this->assertEquals($expectedStylish, genDiff($firstPathJson, $secondPathJson, 'stylish'));
        $this->assertEquals($expectedStylish, genDiff($firstPathYaml, $secondPathYaml, 'stylish'));
        $this->assertEquals($expectedPlain, genDiff($firstPathJson, $secondPathJson, 'plain'));
        $this->assertEquals($expectedPlain, genDiff($firstPathYaml, $secondPathYaml, 'plain'));
    }
}
