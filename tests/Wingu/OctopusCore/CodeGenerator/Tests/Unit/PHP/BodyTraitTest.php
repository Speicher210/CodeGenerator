<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;

class BodyTraitTest extends TestCase {

    public function getDataAddBodyLine() {
    	return array([[], "{\n}"], [[''], "{\n\n}"],
    			[["'my body';", "\$var = 1;", "return \$var;"], file_get_contents(dirname(__DIR__) . '/Expected/PHP/FunctionGeneratorBody1.txt')],
    			[["if (\$var === true) {", "    return 1;", "} else {", "    return 0;", "}"],
    			file_get_contents(dirname(__DIR__) . '/Expected/PHP/FunctionGeneratorBody2.txt')]);
    }

    /**
     * @dataProvider getDataAddBodyLine
     */
    public function testAddBodyLineBody(array $bodyLines, $expected) {
    	$fg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\FunctionGenerator', null, [], '', false);
    	foreach ($bodyLines as $line) {
    		$fg->addBodyLine(new CodeLineGenerator($line));
    	}
    	$this->assertSame($expected, $fg->generateBody());
    }

    public function testAddEmptyBodyLine() {
    	$fg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\FunctionGenerator', null, [], '', false);
    	$fg->addEmptyBodyLine();
    	$fg->addBodyLine(new CodeLineGenerator('line 1'));
    	$fg->addEmptyBodyLine();
    	$fg->addBodyLine(new CodeLineGenerator('line 2'));

    	$this->assertSame("{\n\n    line 1\n\n    line 2\n}", $fg->generateBody());
    }

    public function getDataSetBody() {
    	return array(['', "{\n\n}"], ["line 1\n    line 2\nline 3", "{\n    line 1\n    line 2\nline 3\n}"],
    			["  line 1\n    line 2\nline 3  ", "{\n      line 1\n    line 2\nline 3\n}"]);
    }

    /**
     * @dataProvider getDataSetBody
     */
    public function testSetBodyLine($body, $expected) {
    	$fg = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\FunctionGenerator', null, [], '', false);
    	$fg->setBody($body);

    	$this->assertSame($expected, $fg->generateBody());
    }
}