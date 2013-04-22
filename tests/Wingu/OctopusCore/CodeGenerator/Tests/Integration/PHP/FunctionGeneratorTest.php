<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\FunctionGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ParamTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ReturnTag;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;
use Wingu\OctopusCore\Reflection\ReflectionFunction;
use Wingu\OctopusCore\CodeGenerator\Expression;

require_once(dirname(__DIR__).'/Fixtures/functions.php');

class FunctionGeneratorTest extends TestCase {

    public function testCompleteFunctionGenerator() {
        $code = new FunctionGenerator('myTestFunction');
        $code->setNamespace('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures');

        $functionDoc = new DocCommentGenerator('Short function description.', "Long description.\nOn multiple lines.");
        $functionDoc->addAnnotationTag(new ParamTag('array', 'array', 'The array to sum up.'));
        $functionDoc->addAnnotationTag(new ParamTag('float', 'times', 'The multiplier.'));
        $functionDoc->addAnnotationTag(new ParamTag('\DateTime', 'datetime', 'Dummy variable.'));
        $functionDoc->addAnnotationTag(new ParamTag('integer', 'constant', 'Test some constant.'));
        $functionDoc->addAnnotationTag(new ReturnTag('float'));
        $code->setDocumentation($functionDoc);

        $code->addParameter(new ParameterGenerator('array', null, 'array'));
        $code->addParameter(new ParameterGenerator('times', 1));
        $datetimeParameter = new ParameterGenerator('datetime', null, '\DateTime');
        $datetimeParameter->setDefaultValue(null);
        $code->addParameter($datetimeParameter);
        $code->addParameter(new ParameterGenerator('constant', new Expression('SORT_ASC')));
        $code->addBodyLine(new CodeLineGenerator('return array_sum($array) * $times;'));

        $this->assertSame(file_get_contents(dirname(__DIR__).'/Expected/CompleteFunctionGenerator.txt'), $code->generate());
    }

    public function testFromReflection() {
        $fr = new ReflectionFunction('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\myTestFunction');
        $fg = FunctionGenerator::fromReflection($fr);
        $this->assertSame(file_get_contents(dirname(__DIR__).'/Expected/CompleteFunctionGenerator.txt'), $fg->generate());

        $fr = new ReflectionFunction('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\testFunctionNoDocComment');
        $fg = FunctionGenerator::fromReflection($fr);
        $this->assertSame("namespace Wingu\\OctopusCore\\CodeGenerator\\Tests\\Integration\\Fixtures {\n\n    function testFunctionNoDocComment() {\n\n    }\n}", $fg->generate());
    }
}