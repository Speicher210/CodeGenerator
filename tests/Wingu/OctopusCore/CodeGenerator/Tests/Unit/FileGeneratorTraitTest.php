<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit;

class FileGeneratorTraitTest extends TestCase {

    public function getDataSetGetFilename() {
        return array(
            ['myFile'], ['myFile.php'], ['.htaccess'],
        );
    }

    /**
     * @dataProvider getDataSetGetFilename
     */
    public function testSetGetFileName($filename) {
        $mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\FileGeneratorTrait', [], '', false);
        $mock->setFilename($filename);

        $this->assertSame($mock->getFilename(), $filename);
    }

    public function testWrite() {
        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\Tests\Unit\Fixtures\FileGeneratorTraitMock', ['testfile.txt']);
        $mock->expects($this->any())
            ->method('generate')
            ->will($this->returnValue(__METHOD__));
        $dir = __DIR__.'/Fixtures/AbstractFileGeneratorTest/writabledir';
        $file = $dir.'/testfile.txt';
        $mock->write($dir);

        $this->assertFileExists($file);
        $this->assertSame(__METHOD__, file_get_contents($file));

        @unlink($file);
    }

    public function testWriteNoDirectory() {
        $dir = __DIR__.'/Fixtures/AbstractFileGeneratorTest/writabledir';
        $file = $dir.'/testfile.txt';

        $mock = $this->getMockForAbstractClass('Wingu\OctopusCore\CodeGenerator\Tests\Unit\Fixtures\FileGeneratorTraitMock', [$file]);
        $mock->expects($this->any())
            ->method('generate')
            ->will($this->returnValue(__METHOD__));
        $mock->write();

        $this->assertFileExists($file);
        $this->assertSame(__METHOD__, file_get_contents($file));

        @unlink($file);
    }

    /**
     * @expectedException Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException
     */
    public function testWriteDirectoryNotWritable() {
    	$mock = $this->getObjectForTrait('Wingu\OctopusCore\CodeGenerator\FileGeneratorTrait', ['testfile.txt']);
    	$dir = __DIR__.'/Fixtures/AbstractFileGeneratorTest/__non_writeable_directory__';
    	$mock->write($dir);
    }
}