<?php

namespace Wingu\OctopusCore\CodeGenerator;

/**
 * Class that holds one line of code.
 */
class CodeLineGenerator extends AbstractGenerator
{

    /**
     * The line of code.
     *
     * @var string
     */
    protected $codeLine = '';

    /**
     * Constructor.
     *
     * @param string $codeLine The line of code.
     * @param integer $indentationLevel The current indentation level.
     * @param integer $indentationString The current indentation string.
     */
    public function __construct($codeLine = '', $indentationLevel = null, $indentationString = null)
    {
        $this->codeLine = rtrim($codeLine);

        if ($indentationLevel !== null) {
            $this->setIndentationLevel($indentationLevel);
        }

        if ($indentationString !== null) {
            $this->setIndentationString($indentationString);
        }
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate()
    {
        return $this->getIndentation() . $this->codeLine;
    }
}
