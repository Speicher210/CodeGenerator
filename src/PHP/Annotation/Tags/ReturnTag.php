<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

/**
 * Documentation annotation "return" tag generator.
 */
class ReturnTag extends BaseTag
{

    /**
     * The type of the return value.
     *
     * @var string
     */
    protected $returnType;

    /**
     * Constructor.
     *
     * @param string $returnType The type of the return value.
     * @param string $description The return description / detail.
     */
    public function __construct($returnType, $description = null)
    {
        $this->returnType = $returnType;
        parent::__construct('return', $description);
    }

    /**
     * Generate the description part of the annotation tag.
     *
     * @return string
     */
    protected function generateDescriptionPart()
    {
        return trim($this->returnType . ' ' . $this->description);
    }
}
