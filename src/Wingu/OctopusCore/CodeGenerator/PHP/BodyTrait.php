<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\CodeLineGenerator;

/**
 * Trait for entities that have a body.
 */
trait BodyTrait {

    /**
     * The body as an array of lines.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\CodeLineGenerator[]
     */
    protected $body = array();

    /**
     * Set the complete body.
     *
     * @param string $body The body to set.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator
     */
    public function setBody($body) {
        $this->body = array(new CodeLineGenerator($body));
        return $this;
    }

    /**
     * Add a body line.
     *
     * @param CodeLineGenerator $line The line to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator
     */
    public function addBodyLine(CodeLineGenerator $line) {
        $this->body[] = $line;
        return $this;
    }

    /**
     * Add an empty body line.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator
     */
    public function addEmptyBodyLine() {
        $this->body[] = new CodeLineGenerator();
        return $this;
    }

    /**
     * Generate the body.
     *
     * @return string
     */
    public function generateBody() {
        if (count($this->body) === 0) {
            return '{' . $this->getLineFeed() . $this->getIndentation() . '}';
        } else {
            $code = array();
            $code[] = '{';
            foreach ($this->body as $line) {
                $line->addIndentationLevel($this->getIndentationLevel() + 1);
                $line->setIndentationString($this->getIndentationString());
                $code[] = rtrim($line->generate());
            }

            $code[] = $this->getIndentation() . '}';
            return implode($this->getLineFeed(), $code);
        }
    }
}