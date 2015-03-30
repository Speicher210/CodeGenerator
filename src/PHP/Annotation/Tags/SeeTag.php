<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

/**
 * Documentation annotation "see" tag generator.
 */
class SeeTag extends BaseTag
{

    /**
     * Constructor.
     *
     * @param string $description The tag description / detail.
     */
    public function __construct($description = null)
    {
        parent::__construct('see', $description);
    }
}
