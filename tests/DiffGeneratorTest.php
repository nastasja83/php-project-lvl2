<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffGeneratorTest extends TestCase
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
     * @return array
     */
    private function getFilePaths(): array
    {
        $firstPathJson = $this->getFilePath('first.json');
        $secondPathJson = $this->getFilePath('second.json');
        $firstPathYaml = $this->getFilePath('first.yml');
        $secondPathYaml = $this->getFilePath('second.yml');
        return [$firstPathJson, $secondPathJson, $firstPathYaml, $secondPathYaml];
    }

    /**
     * @return void
     */
    public function testStylish(): void
    {
        $expectedStylish = file_get_contents($this->getFilePath('StylishExpected.txt'));
        [$firstPathJson, $secondPathJson, $firstPathYaml, $secondPathYaml] = $this->getFilePaths();

        $this->assertEquals($expectedStylish, genDiff($firstPathJson, $secondPathJson, 'stylish'));
        $this->assertEquals($expectedStylish, genDiff($firstPathYaml, $secondPathYaml, 'stylish'));
    }
    /**
     * @return void
     */
    public function testPlain(): void
    {
        $expectedPlain = file_get_contents($this->getFilePath('PlainExpected.txt'));
        [$firstPathJson, $secondPathJson, $firstPathYaml, $secondPathYaml] = $this->getFilePaths();

        $this->assertEquals($expectedPlain, genDiff($firstPathJson, $secondPathJson, 'plain'));
        $this->assertEquals($expectedPlain, genDiff($firstPathYaml, $secondPathYaml, 'plain'));
    }

    /**
     * @return void
     */
    public function testJson(): void
    {
        $expectedJson = file_get_contents($this->getFilePath('JsonExpected.txt'));
        [$firstPathJson, $secondPathJson, $firstPathYaml, $secondPathYaml] = $this->getFilePaths();

        $this->assertEquals($expectedJson, genDiff($firstPathJson, $secondPathJson, 'json'));
        $this->assertEquals($expectedJson, genDiff($firstPathYaml, $secondPathYaml, 'json'));
    }
}
