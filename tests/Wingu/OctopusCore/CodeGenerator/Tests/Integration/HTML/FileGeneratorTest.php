<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\HTML;

use Wingu\OctopusCore\CodeGenerator\Tests\Integration\TestCase;
use Wingu\OctopusCore\CodeGenerator\HTML\FileGenerator;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;

class FileGeneratorTest extends TestCase {

    public function getDataGenerate() {
        $lines1 = [];
        $lines2 = ['<!DOCTYPE html>', '<html lang="en">', '<head>', '<title>Testing HTML file generator</title>', '</head>', '<body></body>', '</html>'];
        $lines3 = ['<!DOCTYPE html>', '<html lang="en">', '<head>', '<title>Testing HTML file generator</title>', '</head>'];

        $body1 = null;
        $body2 = null;
        $body3 = '<!DOCTYPE html><html><body></body></html>';

        $expected1 = file_get_contents(__DIR__.'/../Expected/HTMLFileGenerator1.txt');
        $expected2 = file_get_contents(__DIR__.'/../Expected/HTMLFileGenerator2.txt');
        $expected3 = file_get_contents(__DIR__.'/../Expected/HTMLFileGenerator3.txt');

        return array(
            [$lines1, $body1, $expected1],
            [$lines2, $body2, $expected2],
            [$lines3, $body3, $expected3],
        );
    }

    /**
     * @dataProvider getDataGenerate
     */
    public function testGenerate($lines, $body, $expected) {
        $fg = new FileGenerator('test.html');
        foreach ($lines as $line) {
            $fg->addBodyLine(new CodeLineGenerator($line));
        }
        if ($body !== null) {
            $fg->setBody($body);
        }

        $this->assertSame($expected, $fg->generate());
    }

}