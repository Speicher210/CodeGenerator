<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\TraitGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\Modifiers;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\BaseTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ParamTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ReturnTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\ThrowsTag;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\VarTag;
use Wingu\OctopusCore\Reflection\ReflectionClass;

class TraitGeneratorTest extends TestCase {

    public function testCompleteTraitGenerator() {
        $tg = new TraitGenerator('MyTrait', 'Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures');

        $traitDocAnnotation = new BaseTag('baseAnnotation', 'testing');
        $traitDoc = new DocCommentGenerator('Short trait description.', "Long description.\nOn multiple lines.", [$traitDocAnnotation]);
        $tg->setDocumentation($traitDoc);

        $uses = array();
        $uses[] = new UseTraitGenerator('tt1');
        $uses[] = new UseTraitGenerator('tt2', ['tt2::myfunc as myfunc2;', 'tt2::func as func2;']);
        $tg->setTraitUses($uses);

        $properties = array();
        $properties[] = new PropertyGenerator('publicProperty');
        $properties[] = new PropertyGenerator('protectedProperty', 1, Modifiers::MODIFIER_PROTECTED);
        $properties[] = new PropertyGenerator('privateProperty', 'private', Modifiers::MODIFIER_PRIVATE);
        $propertyStatic = new PropertyGenerator('array', array(1,null,'string'), [Modifiers::MODIFIER_PROTECTED | Modifiers::MODIFIER_STATIC]);
        $propertyStaticDoc = new DocCommentGenerator('Static property.', null, [new VarTag('array')]);
        $propertyStatic->setDocumentation($propertyStaticDoc);
        $properties[] = $propertyStatic;
        $tg->setProperties($properties);

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

        $method2 = new MethodGenerator('protectedFunc', 'return count($array);');
        $method2->setVisibility(Modifiers::VISIBILITY_PROTECTED);
        $method2->addParameter(new ParameterGenerator('array', array()));
        $method2Doc = new DocCommentGenerator('Protected function.');
        $method2Doc->addAnnotationTag(new ParamTag('array', 'array', 'The array to count.'));
        $method2Doc->addAnnotationTag(new ReturnTag('integer'));
        $method2->setDocumentation($method2Doc);
        $methods[] = $method2;

        $method3 = new MethodGenerator('privateStaticFunction');
        $method3->setStatic(true);
        $method3->setVisibility(Modifiers::VISIBILITY_PRIVATE);
        $method3->setFinal(true);
        $method3Doc = new DocCommentGenerator('Private static function.', 'This is my long function.');
        $method3Doc->addAnnotationTag(new ParamTag('\DateTime', 'datetime', 'The date time.'));
        $method3Doc->addAnnotationTag(new ReturnTag('boolean'));
        $method3Doc->addAnnotationTag(new ThrowsTag('\InvalidArgumentException', 'If the date is in the past.'));
        $method3->setDocumentation($method3Doc);
        $method3->addParameter(new ParameterGenerator('datetime', null, '\DateTime'));
        $method3->addBodyLine(new CodeLineGenerator('if ($datetime < new \DateTime(\'now\')) {'));
        $method3->addBodyLine(new CodeLineGenerator('throw new \InvalidArgumentException(\'Date is in the past.\');', 1));
        $method3->addBodyLine(new CodeLineGenerator('}'));
        $method3->addBodyLine(new CodeLineGenerator());
        $method3->addBodyLine(new CodeLineGenerator('return true;'));
        $methods[] = $method3;

        $tg->setMethods($methods);

        $this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteTraitGenerator.txt'), $tg->generate());
    }

    public function testFromReflection() {
    	$reflection = new ReflectionClass('Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\MyTrait');
    	$tg = TraitGenerator::fromReflection($reflection);

    	$this->assertSame(file_get_contents(dirname(__DIR__).'/../Expected/CompleteTraitGenerator.txt'), $tg->generate());
    }
}