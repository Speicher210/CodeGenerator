<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

/**
 * Trait that provides a way to set a documentation comment to an object that will be generated.
 */
trait DocCommentTrait {

    /**
     * The documentation generator.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator
     */
    protected $documentation;

    /**
     * Set the documentation.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator $documentation The documentation to set.
     */
    public function setDocumentation(DocCommentGenerator $documentation) {
        $this->documentation = $documentation;
    }

    /**
     * Get the documentation.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator
     */
    public function getDocumentation() {
        return $this->documentation;
    }

    /**
     * Generate the documentation.
     *
     * @return string
     */
    public function generateDocumentation() {
        if ($this->documentation !== null) {
            $this->documentation->setIndentationString($this->getIndentationString());
            $this->documentation->setIndentationLevel($this->getIndentationLevel());
            return $this->documentation->generate();
        } else {
            return null;
        }
    }
}