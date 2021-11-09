<?php

namespace Differ\Cli;

use function Differ\Differ\genDiff;

/**
 * Generating and print diff

 * @return void
 */
function run(): void
{
    $doc = <<<DOC
    gendiff -h

    Generate diff
    
    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
      -h --help                     Show this screen
      -v --version                  Show version
      --format <fmt>                Report format [default: stylish]
    
    DOC;

    $path = \Docopt::handle($doc, array('version' => '0.0.1'));
    $firstFilePath = $path['<firstFile>'];
    $secondFilePath = $path['<secondFile>'];

    print_r(genDiff($firstFilePath, $secondFilePath));
}
