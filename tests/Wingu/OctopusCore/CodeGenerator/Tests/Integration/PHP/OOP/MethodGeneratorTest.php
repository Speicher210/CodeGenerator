<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ParamTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ReturnTag;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\Reflection\ReflectionMethod;
use Wingu\OctopusCore\CodeGenerator\Expression;

class MethodGeneratorTest extends TestCase {

    public function testCompleteMethodGenerator() {
        $method = new MethodGenerator('myTestMethod');
        $method->setFinal(true);
        $method->setVisibility(Modifiers::VISIBILITY_PRIVATE);
        $method->setStatic(true);

        $methodDoc = new DocCommentGenerator('Short method description.', "Long description.\nOn multiple lines.");
        $methodDoc->addAnnotationTag(new ParamTag('array', 'array', 'The array to sum up.'));
        $methodDoc->addAnnotationTag(new ParamTag('float', 'times', 'The multiplier.'));
        $methodDoc->addAnnotationTag(new ParamTag('\DateTime', 'datetime', 'Dummy variable.'));
        $methodDoc->addAnnotationTag(new ParamTag('integer', 'const', 'Sorting direction.'));
        $methodDoc->addAnnotationTag(new ReturnTag('float'));
        $method->setDocumentation($methodDoc);

        $method->addParameter(new ParameterGenerator('array', null, 'array'));
        $method->addParameter(new ParameterGenerator('times', 1));
        $datetimeParameter = new ParameterGenerator('datetime', null, '\DateTime');
        $datetimeParameter->setDefaultValue(null);
        $method->addParameter($datetimeParameter);
        $method->addParameter(new ParameterGenerator('const', new Expression('SORT_ASC')));
        $method->addParameter(new ParameterGenerator('c2', new Expression('OtherClass::SOME_CONSTANT')));
        $method->addParameter(new ParameterGenerator('c3', new Expression('Sub\FixtureClassConstant::SOME_CONSTANT')));
        $method->addParameter(new ParameterGenerator('c4', new Expression('\Wingu\OctopusCore\CodeGenerator\Tests\FixtureClassConstant_123::SOME_CONSTANT')));
        $method->addBodyLine(new CodeLineGenerator('return array_sum($array) * $times;'));

        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteMethodGenerator.txt'), $method->generate());
    }

    public function testFromReflection() {
        $mr = new ReflectionMethod('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\ClassMethods', 'myTestMethod');
        $fg = MethodGenerator::fromReflection($mr);
        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteMethodGenerator.txt'), $fg->generate());

        $mr = new ReflectionMethod('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\ClassMethods', 'myAbstractFunction');
        $fg = MethodGenerator::fromReflection($mr);
        $this->assertSame('abstract protected function myAbstractFunction();', $fg->generate());

        $mr = new ReflectionMethod('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\ClassMethods', 'myAbstractPublicFunction');
        $fg = MethodGenerator::fromReflection($mr);
        $this->assertSame('abstract public function myAbstractPublicFunction();', $fg->generate());
    }
}