<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit;

use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;

class CodeLineGeneratorTest extends TestCase {

    public function getDataGenerate() {
        return array(
            ['$line = 1;', null, null, '$line = 1;'],
            ['$line = 2;', null, '#', '$line = 2;'],
            ['$line = 3;', 1, null, '    $line = 3;'],
            ['$line = 4;', 2, '//', '////$line = 4;'],
            ['    $line = 5;', null, null, '    $line = 5;'],
            ['    $line = 6;    ', null, null, '    $line = 6;'],
            ['    $line = 7;    ', 2, null, '            $line = 7;'],
            ["    \$line = 8;\n    \$line9 = 9; ", 1, null, "        \$line = 8;\n    \$line9 = 9;"],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($codeLine, $indentationLevel, $indentationString, $expected) {
        $clg = new CodeLineGenerator($codeLine, $indentationLevel, $indentationString);
        $this->assertSame($expected, $clg->generate());
    }
}