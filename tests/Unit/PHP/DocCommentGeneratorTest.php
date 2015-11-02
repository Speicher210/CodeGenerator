<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP;

use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;

class DocCommentGeneratorTest extends TestCase
{

    /**
     * Data provider.
     *
     * @return array
     */
    public function getDataDocCommentsDescriptions()
    {
        return array(
            [null, null, null, null],
            ['', null, '', null],
            ["\n", null, "\n", null],
            ['short', 'short', 'long', 'long'],
            ['short', 'short', "long\nlong", "long\nlong"],
            ['short desc ', 'short desc', 'long desc ', 'long desc'],
            [' short desc ', 'short desc', ' long desc ', 'long desc'],
        );
    }

    /**
     * Data provider.
     *
     * @param string $calledBy Name of test called.
     * @return array
     */
    public function getDataDocCommentsGeneration($calledBy)
    {
        $returnMap = array(
            '@param string $p1 Some param 1.',
            '@param string $p2 Some param 2.',
            '@param string $p3 Some param 3.',
            '@return boolean',
            '@throw Exception Some exception.'
        );

        $tags6 = $tags7 = array();
        for ($i = 0; $i < 5; $i++) {
            $mock = $this->getMock(
                'Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface',
                [],
                [],
                'GoodTag' . $calledBy . $i
            );
            $mock->expects($this->any())
                ->method('generate')
                ->will($this->returnValue($returnMap[$i]));

            $tags6[] = $mock;
            $tags7[] = clone $mock;
        }

        return array(
            [null, null, [], "/**\n */"],
            ['short', null, [], "/**\n * short\n */"],
            ['short desc ', null, [], "/**\n * short desc\n */"],
            ['short desc ', 'long desc', [], "/**\n * short desc\n *\n * long desc\n */"],
            ['short desc ', 'long desc ', [], "/**\n * short desc\n *\n * long desc\n */"],
            ['short desc ', "long desc \nlong desc ", [], "/**\n * short desc\n *\n * long desc\n * long desc\n */"],
            [null, null, $tags6, "/**\n * " . implode("\n * ", $returnMap) . "\n */"],
            [
                'short',
                'long description',
                $tags7,
                "/**\n * short\n *\n * long description\n *\n * " . implode("\n * ", $returnMap) . "\n */"
            ],
        );
    }

    /**
     * @dataProvider getDataDocCommentsDescriptions
     */
    public function testSetShortDescription($sd, $expected)
    {
        $docComment = new DocCommentGenerator();
        $docComment->setShortDescription($sd);
        $this->assertSame($expected, $docComment->getShortDescription());
    }

    /**
     * @dataProvider getDataDocCommentsDescriptions
     */
    public function testGetShortDescription($sd, $expected)
    {
        $docComment = new DocCommentGenerator($sd);
        $this->assertSame($expected, $docComment->getShortDescription());
    }

    /**
     * @dataProvider getDataDocCommentsDescriptions
     */
    public function testSetLongDescription($sd, $expectedShortDescription, $ld, $expectedLongDescription)
    {
        $docComment = new DocCommentGenerator();
        $docComment->setLongDescription($ld);
        $this->assertSame($expectedLongDescription, $docComment->getLongDescription());
    }

    /**
     * @dataProvider getDataDocCommentsDescriptions
     */
    public function testGetLongDescription($sd, $expectedShortDescription, $ld, $expectedLongDescription)
    {
        $docComment = new DocCommentGenerator(null, $ld);
        $this->assertSame($expectedLongDescription, $docComment->getLongDescription());
    }

    /**
     * @dataProvider getDataDocCommentsGeneration
     */
    public function testGetAnnotationTags($sd, $ld, $tags)
    {
        $docComment = new DocCommentGenerator(null, null, $tags);
        $this->assertSame($tags, $docComment->getAnnotationTags());
    }

    /**
     * @dataProvider getDataDocCommentsGeneration
     */
    public function testGenerate($sd, $ld, $tags, $expected)
    {
        $docComment = new DocCommentGenerator($sd, $ld, $tags);
        $this->assertSame($expected, $docComment->generate());
    }

    public function testFromReflection()
    {
        $rmShortDescription = 'short description';
        $rmLongDescription = 'long description';
        $rmAnnotationsCollection = $this->getMock(
            'Wingu\OctopusCore\Reflection\Annotation\AnnotationsCollection',
            ['getAnnotations'],
            [],
            '',
            false
        );
        $reflectionAnnotationTagMock = $this->getMock('Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface');
        $reflectionAnnotationTagMock->expects($this->any())->method('getTagName')->will($this->returnValue('myTag'));
        $reflectionAnnotationTagMock->expects($this->any())->method('getDescription')->will(
            $this->returnValue('my description')
        );
        $rmAnnotations = [$reflectionAnnotationTagMock];
        $rmAnnotationsCollection->expects($this->any())
            ->method('getAnnotations')
            ->will($this->returnValue($rmAnnotations));

        $reflectionMock = $this->getMock(
            'Wingu\OctopusCore\Reflection\ReflectionDocComment',
            ['getShortDescription', 'getLongDescription', 'getAnnotationsCollection'],
            [],
            '',
            false
        );
        $reflectionMock->expects($this->any())
            ->method('getShortDescription')
            ->will($this->returnValue($rmShortDescription));
        $reflectionMock->expects($this->any())
            ->method('getLongDescription')
            ->will($this->returnValue($rmLongDescription));
        $reflectionMock->expects($this->any())
            ->method('getAnnotationsCollection')
            ->will($this->returnValue($rmAnnotationsCollection));

        $dcg = DocCommentGenerator::fromReflection($reflectionMock);

        $this->assertSame($rmShortDescription, $dcg->getShortDescription());
        $this->assertSame($rmLongDescription, $dcg->getLongDescription());

        $tags = $dcg->getAnnotationTags();
        $this->assertCount(1, $tags);
        $this->assertSame('myTag', $tags[0]->getTagName());
        $this->assertSame('my description', $tags[0]->getDescription());
    }

    public function testRemoveTagsByName()
    {
        $docCommentGenerator = new DocCommentGenerator();
        $ag = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\Annotation\AnnotationGenerator');
        $ag->expects($this->once())->method('removeTagsByName')->with('tagName');
        $this->setProperty($docCommentGenerator, 'annotationGenerator', $ag);
        $docCommentGenerator->removeAnnotationTagsByName('tagName');
    }
}