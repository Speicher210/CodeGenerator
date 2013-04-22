<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit\PHP\Annotation;

use Wingu\OctopusCore\CodeGenerator\Tests\Unit\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\AnnotationGenerator;

class AnnotationGeneratorTest extends TestCase {

    protected function getDataGoodTags() {
        $tags = array();

        for($i=0; $i<5; $i++) {
            $tags[] = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface', [], [], 'GoodTag'.$i);
        }

        return $tags;
    }

    public function testSetTags() {
        $tags = $this->getDataGoodTags();

        $ag = new AnnotationGenerator();
        $ag->setTags($tags);

        $this->assertSame($tags, $ag->getTags());
    }

    public function testAddTags() {
        $tags = $this->getDataGoodTags();

        $half = floor(count($tags)/2);
        $tags1 = array_slice($tags, 0, $half);
        $tags2 = array_slice($tags, $half);

        $ag = new AnnotationGenerator();
        $ag->addTags($tags1);
        $ag->addTags($tags2);

        $this->assertSame($tags, $ag->getTags());
    }

    public function testAddTag() {
    	$tags = $this->getDataGoodTags();

    	$ag = new AnnotationGenerator();
    	foreach ($tags as $tag) {
    	    $ag->addTag($tag);
    	}

    	$this->assertSame($tags, $ag->getTags());
    }

    public function getDataGenerate() {
        $returnMap = array(
    		'@param string $p1 Some param 1.',
    		'@param string $p2 Some param 2.',
    		'@param string $p3 Some param 3.',
    		'@return boolean',
    		'@throw Exception Some exception.'
        );

        return array([
            $returnMap,
            file_get_contents(__DIR__.'/../../Expected/agNoIndent.txt'),
            file_get_contents(__DIR__.'/../../Expected/agIndent.txt')
        ]);
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($returnMap, $expectedResultNoIndent) {
        $ag = new AnnotationGenerator();

        $mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface');
        foreach ($returnMap as $at => $generated) {
            $mock->expects($this->at($at))
                ->method('generate')
                ->will($this->returnValue($generated));
            $ag->addTag($mock);
        }

        $this->assertSame($expectedResultNoIndent, $ag->generate());
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerateToString($returnMap, $expectedResultNoIndent) {
    	$ag = new AnnotationGenerator();

    	$mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface');
    	foreach ($returnMap as $at => $generated) {
    		$mock->expects($this->at($at))
        		->method('generate')
        		->will($this->returnValue($generated));
    		$ag->addTag($mock);
    	}

    	$this->assertSame($expectedResultNoIndent, (string)$ag);
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerateIndentation1($returnMap, $expectedResultNoIndent, $expectedResultIndent) {
    	$ag = new AnnotationGenerator();
    	$ag->setIndentationLevel(1);

    	$mock = $this->getMock('Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface');
    	foreach ($returnMap as $at => $generated) {
    		$mock->expects($this->at($at))
    		->method('generate')
    		->will($this->returnValue($generated));
    		$ag->addTag($mock);
    	}

    	$this->assertSame($expectedResultIndent, $ag->generate());
    }

}