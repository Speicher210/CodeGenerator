<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\Fixtures;

use Wingu\OctopusCore\CodeGenerator\FileGeneratorTrait;

abstract class FileGeneratorTraitMock
{
    use FileGeneratorTrait;

    public function __construct($filename)
    {
        $this->setFilename($filename);
    }

    abstract public function generate();
}