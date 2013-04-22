<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator;

class InterfaceGeneratorTest extends TestCase {

    public function getDataExtends() {
        return array(
            [['i1', 'i2', 'i3'], ['i1'], []],
        );
    }

    /**
     * @dataProvider getDataExtends
     */
    public function testExtends($extends) {
        $mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator', null, [], '', false);
        $mock->setExtends($extends);

        $this->assertCount(count($extends), $mock->getExtends());
        foreach ($extends as $extend) {
            $this->assertTrue(in_array($extend, $mock->getExtends()));
        }
    }

    public function getDataExtendsException() {
    	return array(
			[['inter face']], [['1i']], [[null]],
    	);
    }

    /**
     * @dataProvider getDataExtendsException
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testExtendsException($extends) {
        $mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator', null, [], '', false);
        $mock->setExtends($extends);
    }

    protected function getMethodMock($name, $doc, $signature) {
    	$method = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator', ['getName', 'generateDocumentation', 'generateSignature'], [], '', false);
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

    protected function getConstantMock($name, $generate) {
    	$const = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator', ['getName', 'generate'], [], '', false);
    	$const->expects($this->any())
    	    ->method('getName')
    	    ->will($this->returnValue($name));
    	$const->expects($this->any())
    	    ->method('generate')
    	    ->will($this->returnValue($generate));
    	return $const;
    }

    public function getDataGenerate() {
        $doc3 = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator', ['generate']);
        $doc3->expects($this->any())
            ->method('generate')
            ->will($this->returnValue("    /**\n     * My description\n     */"));

        $methods3 = $constants3 = array();
        $doc3Method1Result = "        /**\n * My function 1.\n *\n * My long description.\n *\n * @param array \$param1 The param 1.\n * @param string \$param2 The param 2.\n * @return mixed\n */";
        $doc3Method1Result = implode("\n        ", explode("\n", $doc3Method1Result));
        $methods3[] = $this->getMethodMock('myFunction1', $doc3Method1Result, "        public function myFunction1(array \$param1 = array(1, null, 'string'), \$param2 = null)");
        $methods3[] = $this->getMethodMock('simple', null, "        public static function simple()");

        $doc3Const1Result = "        /**\n * My const.\n *\n * @var bolean\n */\nconst MY_CONST = true;";
        $doc3Const1Result = implode("\n        ", explode("\n", $doc3Const1Result));
        $constants3[] = $this->getConstantMock('MY_CONST', $doc3Const1Result);

        return array(
            ['i1', null, [], [], [], null, file_get_contents(dirname(__DIR__).'/../Expected/PHP/OOP/InterfaceGenerator1.txt')],
            ['i2', 'myNamespace', ['ie1', 'ie2'], [], [], null, file_get_contents(dirname(__DIR__).'/../Expected/PHP/OOP/InterfaceGenerator2.txt')],
            ['i3', 'myNamespace', ['\othernamespace\ie1', 'ie2'], $constants3, $methods3 , $doc3, file_get_contents(dirname(__DIR__).'/../Expected/PHP/OOP/InterfaceGenerator3.txt')],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($name, $ns, array $extends, array $constants, array $methods, $doc, $expected) {
        $ig = new InterfaceGenerator($name, $ns);
        $ig->setExtends($extends);
        $ig->setConstants($constants);
        $ig->setMethods($methods);
        if ($doc !== null) {
            $ig->setDocumentation($doc);
        }

        $ig->setConstants($constants);

        $this->assertSame($expected, $ig->generate());
    }

    /**
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException
     */
    public function testGenerateBadMethodVisibility() {
        $method = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator', ['getName', 'getVisibility'], [], '', false);
    	$method->expects($this->any())
    	    ->method('getName')
    	    ->will($this->returnValue('func'));
    	$method->expects($this->any())
        	->method('getVisibility')
        	->will($this->returnValue(Modifiers::VISIBILITY_PROTECTED));
        $ig = new InterfaceGenerator('myinterface');
        $ig->addMethod($method);
        $r = $ig->generate();
    }

    /**
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testFromReflectionNotInterfaceClass() {
    	$reflection = $this->getMock('Wingu\OctopusCore\Reflection\ReflectionClass', ['isInterface'], [], '', false);
    	$reflection->expects($this->any())
        	->method('isInterface')
        	->will($this->returnValue(false));
    	InterfaceGenerator::fromReflection($reflection);
    }
}