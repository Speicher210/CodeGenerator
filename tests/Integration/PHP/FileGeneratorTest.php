<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\PHP;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\BaseTag;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator;

class FileGeneratorTest extends TestCase {

    public function getDataGenerate() {
        $ns1 = 'myNS\subNS';
        $ns2 = 'myNS\subNS2';
        $ns3 = 'myNS\subNS3';
        $ns4 = 'myNS\subNS4';

        $requiredFiles = ['r1', 'r2'];
        $uses = ['use1', ['use2', 'use2_alias']];
        $body1 = '$var = 1;';
        $body2 = "<?php\n\n\$var = 1;";
        $body3 = "<?php\n\n\$var3 = 1;";

        $doc = new DocCommentGenerator('Shortfile description', "Long file description.\nOn multiple lines.");
        $doc->addAnnotationTag(new BaseTag('author', 'Wingu'));

        $objects1 = $objects2 = $objects3 = $objects4 = array();
        $objects2[] = (new ClassGenerator('class2_1'))->addUse('classUse', 'classUseAlias');
        $objects2[] = new InterfaceGenerator('interface2_1');
        $objects3[] = new ClassGenerator('class3_1', 'myObjects\classes');
        $objects3[] = new ClassGenerator('class3_2', 'myObjects\classes');
        $objects3[] = new interfaceGenerator('interface3_1', 'myObjects\interfaces');
        $objects4[] = new ClassGenerator('class4_1', 'myNS\subNS4');
        $objects4[] = new InterfaceGenerator('interface4_1');

        $expected1 = file_get_contents(__DIR__.'/../Expected/PHPFileGenerator1.txt');
        $expected2 = file_get_contents(__DIR__.'/../Expected/PHPFileGenerator2.txt');
        $expected3 = file_get_contents(__DIR__.'/../Expected/PHPFileGenerator3.txt');
        $expected4 = file_get_contents(__DIR__.'/../Expected/PHPFileGenerator4.txt');

        return array(
            [$doc, $ns1, $requiredFiles, $uses, $objects1, $body1, $expected1],
            [$doc, $ns2, $requiredFiles, $uses, $objects2, $body2, $expected2],
            [$doc, $ns3, $requiredFiles, $uses, $objects3, $body3, $expected3],
            [$doc, $ns4, [], [], $objects4, null, $expected4],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($doc, $ns, $required, $uses, $objects, $body, $expected) {
        $fg = new FileGenerator('test.php');
        $fg->setDocumentation($doc);
        $fg->setNamespace($ns);
        $fg->setRequiredFiles($required);
        $fg->setUses($uses);
        $fg->setExtraBody($body);
        $fg->setObjects($objects);

        $this->assertSame($expected, $fg->generate());
    }

}