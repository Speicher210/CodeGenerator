<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

/**
 * Documentation annotation "throws" tag generator.
 */
class ThrowsTag extends BaseTag {

    /**
     * The exception thrown.
     *
     * @var string
     */
    protected $exception;

    /**
     * The description for thw throw.
     *
     * @var string
     */
    protected $throwsDescription;

    /**
     * Constructor.
     *
     * @param string $exception The exception thrown.
     * @param string $description The exception description / detail.
     */
    public function __construct($exception, $description = null) {
        $this->exception = $exception;
        $this->throwsDescription = $description;
        parent::__construct('throws');
    }

    /**
     * Generate the description part of the annotation tag.
     *
     * @return string
     */
    protected function generateDescriptionPart() {
        return trim($this->exception . ' ' . $this->throwsDescription);
    }
}