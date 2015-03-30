<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\BaseTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ParamTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ReturnTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ThrowsTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\VarTag;
use Wingu\OctopusCore\Reflection\ReflectionClass;

class InterfaceGeneratorTest extends TestCase {

    public function testCompleteInterfaceGenerator() {
        $ig = new InterfaceGenerator('MyInterface', 'Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures');
        $ig->setExtends(['i1', 'i2', '\Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Dummy\i3', '\Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Dummy\i4']);

        $interfaceDocAnnotation = new BaseTag('baseAnnotation', 'testing');
        $classDoc = new DocCommentGenerator('Short interface description.', "Long description.\nOn multiple lines.", [$interfaceDocAnnotation]);
        $ig->setDocumentation($classDoc);

        $constants = array();
        $constants[] = new ClassConstantGenerator('MY_CONST1', null);
        $constant2Doc = new DocCommentGenerator('My second constant.');
        $constant2Doc->addAnnotationTag(new VarTag('string'));
        $constants[] = new ClassConstantGenerator('MY_CONST2', "it's a string", $constant2Doc);
        $ig->setConstants($constants);

        $methods = array();
        $method1 = new MethodGenerator('__construct');
        $method1Doc = new DocCommentGenerator('Constructor.');
        $method1Doc->addAnnotationTag(new ParamTag('mixed', 'param1', 'Parameter 1.'));
        $method1Doc->addAnnotationTag(new ParamTag('string', 'param2', 'Parameter 2.'));
        $method1->setDocumentation($method1Doc);
        $method1->addParameter(new ParameterGenerator('param1'));
        $method1Param2 = new ParameterGenerator('param2');
        $method1Param2->setDefaultValue(null);
        $method1->addParameter($method1Param2);
        $methods[] = $method1;

        $method2 = new MethodGenerator('publicFunc', 'return count($array);');
        $method2->addParameter(new ParameterGenerator('array', array()));
        $method2Doc = new DocCommentGenerator('Protected function.');
        $method2Doc->addAnnotationTag(new ParamTag('array', 'array', 'The array to count.'));
        $method2Doc->addAnnotationTag(new ReturnTag('integer'));
        $method2->setDocumentation($method2Doc);
        $methods[] = $method2;

        $method3 = new MethodGenerator('publicStaticFunction');
        $method3->setStatic(true);
        $method3Doc = new DocCommentGenerator('Private static function.', 'This is my long function.');
        $method3Doc->addAnnotationTag(new ParamTag('\DateTime', 'datetime', 'The date time.'));
        $method3Doc->addAnnotationTag(new ReturnTag('boolean'));
        $method3Doc->addAnnotationTag(new ThrowsTag('\InvalidArgumentException', 'If the date is in the past.'));
        $method3->setDocumentation($method3Doc);
        $method3->addParameter(new ParameterGenerator('datetime', null, '\DateTime'));
        $methods[] = $method3;

        $ig->setMethods($methods);

        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteInterfaceGenerator.txt'), $ig->generate());
    }

    public function testFromReflection() {
        $reflection = new ReflectionClass('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\MyInterface');
        $ig = InterfaceGenerator::fromReflection($reflection);
        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteInterfaceGenerator.txt'), $ig->generate());
    }
}