<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator;

class ClassGeneratorTest extends TestCase {

    public function getDataExtends() {
        return array(
            ['c1'], ['\MyNS\MyClass'], [null],
        );
    }

    /**
     * @dataProvider getDataExtends
     */
    public function testExtends($extends) {
        $mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator', null, [], '', false);
        $mock->setExtends($extends);
        $this->assertSame($extends, $mock->getExtends());
    }

    public function getDataExtendsException() {
    	return array(
			['other class'], ['1c'], ['class']
    	);
    }

    /**
     * @dataProvider getDataExtendsException
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testExtendsException($extends) {
        $mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator', null, [], '', false);
        $mock->setExtends($extends);
    }

    public function getDataImplements() {
    	return array(
			[['i1', 'i2', 'i3', '\MyNS\MyInterface', '\MyInterface2']]
    	);
    }

    /**
     * @dataProvider getDataImplements
     */
    public function testImplements(array $implements) {
    	$mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator', null, [], '', false);
    	$mock->setImplements($implements);

    	$this->assertCount(count($implements), $mock->getImplements());
    	foreach ($implements as $implement) {
    		$this->assertTrue(in_array($implement, $mock->getImplements()));
    	}
    }

    public function getDataImplementsException() {
    	return array(
			[['inter face']], [['1c']], [['interface']]
    	);
    }

    /**
     * @dataProvider getDataImplementsException
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testImplementsException($extends) {
    	$mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator', null, [], '', false);
    	$mock->setImplements($extends);
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

    protected function getUseMock($name, $generate) {
    	$use = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator', ['generate'], [], '', false);
    	$use->expects($this->any())
        	->method('generate')
        	->will($this->returnValue($generate));
    	return $use;
    }

    protected function getPropertyMock($name, $generate) {
    	$property = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator', ['getName', 'generate'], [], '', false);
    	$property->expects($this->any())
        	->method('getName')
        	->will($this->returnValue($name));
    	$property->expects($this->any())
        	->method('generate')
        	->will($this->returnValue($generate));
    	return $property;
    }

    public function getDataGenerate() {
        $doc3 = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator', ['generate']);
        $doc3->expects($this->any())
            ->method('generate')
            ->will($this->returnValue("    /**\n     * My description\n     */"));

        $methods3 = $constants3 = $uses3 = $properties3 = array();
        $doc3Method1Result = "        /**\n * My function 1.\n *\n * My long description.\n *\n * @param array \$param1 The param 1.\n * @param string \$param2 The param 2.\n * @return mixed\n */";
        $doc3Method1Result = implode("\n        ", explode("\n", $doc3Method1Result));
        $methods3[] = $this->getMethodMock('myFunction1', $doc3Method1Result, "        public function myFunction1(array \$param1 = array(1, null, 'string'), \$param2 = null)");
        $methods3[] = $this->getMethodMock('simple', null, "        public static function simple()");

        $doc3Const1Result = "        /**\n * My const.\n *\n * @var bolean\n */\nconst MY_CONST = true;";
        $doc3Const1Result = implode("\n        ", explode("\n", $doc3Const1Result));
        $constants3[] = $this->getConstantMock('MY_CONST', $doc3Const1Result);

        $uses3[] = $this->getUseMock('MyTrait1', '        use MyTrait1;');
        $uses3[] = $this->getUseMock('MyTrait2', "        use MyTrait2 {\n            func as funcalias;\n        }");

        $properties3[] = $this->getPropertyMock('property1', '        public $property1 = 1;');
        $property2Result = "        /**\n * My property.\n *\n * @var string\n */\nprotected \$property2 = 'mystring';";
        $property2Result = implode("\n        ", explode("\n", $property2Result));
        $properties3[] = $this->getPropertyMock('property2', $property2Result);

        return array(
            ['c1', null, [Modifiers::MODIFIER_FINAL], null, [], [], [], [], [], null, file_get_contents(dirname(__DIR__).'/../Expected/PHP/OOP/ClassGenerator1.txt')],
            ['c2', 'myNamespace', [Modifiers::MODIFIER_ABSTRACT], null, ['ie1', 'ie2'], [], [], [], [], null, file_get_contents(dirname(__DIR__).'/../Expected/PHP/OOP/ClassGenerator2.txt')],
            ['c3', 'myNamespace', [], 'myExtendedClass', ['\othernamespace\ie1', 'ie2'], $uses3, $constants3, $properties3, $methods3 , $doc3, file_get_contents(dirname(__DIR__).'/../Expected/PHP/OOP/ClassGenerator3.txt')],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($name, $ns, array $modifiers, $extends, array $implements, array $uses, array $constants, array $properties, array $methods, $doc, $expected) {
        $ig = new ClassGenerator($name, $ns);
        $ig->setExtends($extends);
        $ig->setImplements($implements);
        $ig->setTraitUses($uses);
        $ig->setConstants($constants);
        $ig->setProperties($properties);
        $ig->setMethods($methods);
        if ($doc !== null) {
            $ig->setDocumentation($doc);
        }

        $ig->setConstants($constants);

        foreach ($modifiers as $modifier) {
            $this->callMethod($ig, 'addModifier', [$modifier]);
        }

        $this->assertSame($expected, $ig->generate());
    }

    public function getDataFromReflectionNotAClass() {
        $interface = $this->getMock('Wingu\OctopusCore\Reflection\ReflectionClass', ['isInterface'], [], '', false);
    	$interface->expects($this->any())
        	->method('isInterface')
        	->will($this->returnValue(true));

    	$trait = $this->getMock('Wingu\OctopusCore\Reflection\ReflectionClass', ['isInterface', 'isTrait'], [], '', false);
    	$trait->expects($this->any())
        	->method('isInterface')
        	->will($this->returnValue(false));
    	$trait->expects($this->any())
        	->method('isTrait')
        	->will($this->returnValue(true));

        return array(
            [$interface],[$trait]
        );
    }

    /**
     * @dataProvider getDataFromReflectionNotAClass
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException
     */
    public function testFromReflectionNotAClass($reflection) {
    	ClassGenerator::fromReflection($reflection);
    }
}