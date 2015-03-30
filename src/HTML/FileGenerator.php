<?php

namespace Wingu\OctopusCore\CodeGenerator\HTML;

use Wingu\OctopusCore\CodeGenerator\AbstractGenerator;
use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;
use Wingu\OctopusCore\CodeGenerator\FileGeneratorTrait;

/**
 * Class to generate HTML files.
 */
class FileGenerator extends AbstractGenerator
{

    use FileGeneratorTrait;

    /**
     * The code in the file.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\CodeLineGenerator[]
     */
    protected $code = array();

    /**
     * Constructor.
     *
     * @param string $filename The name of the file.
     */
    public function __construct($filename)
    {
        $this->setFilename($filename);
    }

    /**
     * Set the body of the file.
     *
     * @param string $body The body.
     * @return \Wingu\OctopusCore\CodeGenerator\HTML\FileGenerator
     */
    public function setBody($body)
    {
        $this->code = array(new CodeLineGenerator($body, $this->getIndentationLevel(), $this->getIndentationString()));

        return $this;
    }

    /**
     * Add a new line of code to the file.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\CodeLineGenerator $line The line of code to add.
     * @return \Wingu\OctopusCore\CodeGenerator\HTML\FileGenerator
     */
    public function addBodyLine(CodeLineGenerator $line)
    {
        $this->code[] = $line;

        return $this;
    }

    /**
     * Get the code of the file.
     *
     * @return string
     */
    public function getBody()
    {
        return implode($this->getLineFeed(), $this->code);
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate()
    {
        return $this->getBody();
    }
}
