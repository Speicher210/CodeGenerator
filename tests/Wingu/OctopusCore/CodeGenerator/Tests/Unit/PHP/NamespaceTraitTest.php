<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class NamespaceTraitTest extends TestCase {

    public function getDataExtractNamespaceFromQualifiedName() {
        return array(
            ['name', null], ['my\ns\name', 'my\ns'], ['\myns\name', '\myns']
        );
    }

    /**
     * @dataProvider getDataExtractNamespaceFromQualifiedName
     */
    public function testExtractNamespaceFromQualifiedName($name, $expected) {
        $trait = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\PHP\NamespaceTrait');
        $this->assertSame($expected, $trait->extractNamespaceFromQualifiedName($name));
    }

}