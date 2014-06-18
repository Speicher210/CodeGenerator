<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class DocCommentTraitTest extends TestCase
{

    public function testSetGetDocComment()
    {
        $docComment = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator');
        $trait = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\DocCommentTrait');

        $trait->setDocumentation($docComment);
        $this->assertSame($docComment, $trait->getDocumentation());
    }
}