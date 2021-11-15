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

    $params = \Docopt::handle($doc);
    $firstFilePath = $params['<firstFile>'];
    $secondFilePath = $params['<secondFile>'];
    $format = $params['--format'];

    $diff = genDiff($firstFilePath, $secondFilePath, $format);
    printf($diff);
}
