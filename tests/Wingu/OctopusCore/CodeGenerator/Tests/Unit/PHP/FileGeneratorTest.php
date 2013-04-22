<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class FileGeneratorTest extends TestCase {

    public function testGetSetRequiredFiles() {
        $mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator', null, [], '', false);

        $mock->setRequiredFiles(['s1', 's2', 's3']);
        $mock->setRequiredFiles(['s1', 's2']);
        $mock->addRequiredFiles(['as1', 'as2']);
        $mock->addRequiredFile('a1');
        $mock->addRequiredFile('a2');

        $this->assertSame(['s1', 's2', 'as1', 'as2', 'a1', 'a2'], $mock->getRequiredFiles());
    }

    public function getDataExtraBody() {
        return array(
            ['my body'], ['$var = 1;']
        );
    }

    /**
     * @dataProvider getDataExtraBody
     */
    public function testSetGetExtraBody($body) {
        $mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator', null, [], '', false);
        $mock->setExtraBody($body);

        $this->assertSame($body, $mock->getExtraBody());
    }
}