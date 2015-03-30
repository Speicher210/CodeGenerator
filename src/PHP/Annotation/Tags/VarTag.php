<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

/**
 * Documentation annotation "var" tag generator.
 */
class VarTag extends BaseTag
{

    /**
     * The type of the variable.
     *
     * @var string
     */
    protected $varType;

    /**
     * Constructor.
     *
     * @param string $varType The type of the variable.
     */
    public function __construct($varType)
    {
        $this->varType = trim($varType);
        parent::__construct('var', $this->varType);
    }
}
