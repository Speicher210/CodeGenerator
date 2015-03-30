<?php

namespace Wingu\OctopusCore\CodeGenerator;

/**
 * Expression that does not need processing.
 */
class Expression
{

    /**
     * The expression.
     *
     * @var mixed
     */
    protected $expression;

    /**
     * Constructor.
     *
     * @param mixed $expression The expression.
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * Get the original expression.
     *
     * @return mixed
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Magic method for printing the expression.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->expression;
    }
}