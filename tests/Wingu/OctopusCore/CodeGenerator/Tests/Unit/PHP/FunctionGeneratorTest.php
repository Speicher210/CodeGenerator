<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\FunctionGenerator;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;

class FunctionGeneratorTest extends TestCase {

    protected function getParamForSignature($identifier) {
        $param = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator', ['generate', 'getName'], [], '', false);
        $param->expects($this->any())->method('getName')->will($this->returnValue('param' . $identifier));
        $param->expects($this->any())->method('generate')->will($this->returnValue('$param' . $identifier));

        return $param;
    }

    public function getDataGenerateSignature() {
        $params = array();
        $params[] = $this->getParamForSignature(1);
        $params[] = $this->getParamForSignature(2);
        $params[] = $this->getParamForSignature(3);

        return array(['name1', [], 'function name1()'], ['name2', $params, 'function name2($param1, $param2, $param3)']);
    }

    /**
     * @dataProvider getDataGenerateSignature
     */
    public function testGenerateSignature($name, $params, $expected) {
        $fg = new FunctionGenerator($name, null, $params);
        $this->assertSame($expected, $fg->generateSignature());
    }

    public function getDataGenerate() {
        $docResult = "/**\n * My function.\n *\n * My nice function\n * @param mixed \$param1 My param1.\n * @return mixed\n */";
        $doc = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator', ['generate']);
        $doc->expects($this->any())
            ->method('generate')
            ->will($this->returnValue($docResult));
        return array(
            ['func1', 'myns', null, [], null, "namespace myns {\n\n    function func1() {\n    }\n}"],
            ['func2', null, 'return __DIR__;', [], null, "function func2() {\n    return __DIR__;\n}"],
            ['func3', null, "\$var = 1;\nreturn \$var1;", [$this->getParamForSignature(1)], null, "function func3(\$param1) {\n    \$var = 1;\nreturn \$var1;\n}"],
            ['func4', null, ['$var = 1;','return $var1;'], [$this->getParamForSignature(1)], null, "function func4(\$param1) {\n    \$var = 1;\n    return \$var1;\n}"],
            ['func4', null, ['$var = 1;','return $var1;'], [$this->getParamForSignature(1)], $doc, $docResult."\nfunction func4(\$param1) {\n    \$var = 1;\n    return \$var1;\n}"],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($name, $ns, $body, $params, $doc, $expected) {
        if (is_array($body)) {
            $fg = new FunctionGenerator($name, null, $params);
            foreach ($body as $bodyLine) {
                $fg->addBodyLine(new CodeLineGenerator($bodyLine));
            }
        } else {
             $fg = new FunctionGenerator($name, $body, $params);
        }

        if ($ns !== null) {
            $fg->setNamespace($ns);
        }

        if ($doc !== null) {
            $fg->setDocumentation($doc);
        }

        $this->assertSame($expected, $fg->generate());
    }
}