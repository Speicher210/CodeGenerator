<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\PHP\OOP\TraitGenerator;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class TraitGeneratorTest extends TestCase
{

    protected function getMethodMock($name, $doc, $signature)
    {
        $method = $this->getMock(
            'Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator',
            ['getName', 'generateDocumentation', 'generateSignature'],
            [],
            '',
            false
        );
        $method->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));
        $method->expects($this->any())
            ->method('generateDocumentation')
            ->will($this->returnValue($doc));
        $method->expects($this->any())
            ->method('generateSignature')
            ->will($this->returnValue($signature));

        return $method;
    }

    protected function getUseMock($name, $generate)
    {
        $use = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator', ['generate'], [], '', false);
        $use->expects($this->any())
            ->method('generate')
            ->will($this->returnValue($generate));
        return $use;
    }

    protected function getPropertyMock($name, $generate)
    {
        $property = $this->getMock(
            'Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator',
            ['getName', 'generate'],
            [],
            '',
            false
        );
        $property->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));
        $property->expects($this->any())
            ->method('generate')
            ->will($this->returnValue($generate));
        return $property;
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataGenerate()
    {
        $doc3 = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator', ['generate']);
        $doc3->expects($this->any())
            ->method('generate')
            ->will($this->returnValue("    /**\n     * My description\n     */"));

        $methods3 = $constants3 = $uses3 = $properties3 = array();
        $doc3Method1Result = "        /**\n * My function 1.\n *\n * My long description.\n *\n * @param array \$param1 The param 1.\n * @param string \$param2 The param 2.\n * @return mixed\n */";
        $doc3Method1Result = implode("\n        ", explode("\n", $doc3Method1Result));
        $methods3[] = $this->getMethodMock(
            'myFunction1',
            $doc3Method1Result,
            "        public function myFunction1(array \$param1 = array(1, null, 'string'), \$param2 = null)"
        );
        $methods3[] = $this->getMethodMock('simple', null, "        public static function simple()");

        $uses3[] = $this->getUseMock('MyTrait1', '        use MyTrait1;');
        $uses3[] = $this->getUseMock('MyTrait2', "        use MyTrait2 {\n            func as funcalias;\n        }");

        $properties3[] = $this->getPropertyMock('property1', '        public $property1 = 1;');
        $property2Result = "        /**\n * My property.\n *\n * @var string\n */\nprotected \$property2 = 'mystring';";
        $property2Result = implode("\n        ", explode("\n", $property2Result));
        $properties3[] = $this->getPropertyMock('property2', $property2Result);

        return array(
            [
                't1',
                null,
                [],
                [],
                [],
                null,
                file_get_contents(dirname(__DIR__) . '/../Expected/PHP/OOP/TraitGenerator1.txt')
            ],
            [
                't2',
                'myNamespace',
                [],
                [],
                [],
                null,
                file_get_contents(dirname(__DIR__) . '/../Expected/PHP/OOP/TraitGenerator2.txt')
            ],
            [
                't3',
                'myNamespace',
                $uses3,
                $properties3,
                $methods3,
                $doc3,
                file_get_contents(dirname(__DIR__) . '/../Expected/PHP/OOP/TraitGenerator3.txt')
            ],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($name, $ns, array $uses, array $properties, array $methods, $doc, $expected)
    {
        $tg = new TraitGenerator($name, $ns);
        $tg->setTraitUses($uses);
        $tg->setProperties($properties);
        $tg->setMethods($methods);
        if ($doc !== null) {
            $tg->setDocumentation($doc);
        }

        $this->assertSame($expected, $tg->generate());
    }

    /**
     * @expectedException \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testFromReflectionNotTrait()
    {
        $reflection = $this->getMock('Wingu\OctopusCore\Reflection\ReflectionClass', ['isTrait'], [], '', false);
        $reflection->expects($this->any())
            ->method('isTrait')
            ->will($this->returnValue(false));
        TraitGenerator::fromReflection($reflection);
    }

}