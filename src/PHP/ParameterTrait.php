<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Trait for entities that have parameters (functions, methods).
 */
trait ParameterTrait
{

    /**
     * The parameters the entity has.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator[]
     */
    protected $parameters = array();

    /**
     * Set the parameters for the entity.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator[] $parameters The Parameters to set.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the a parameter with the same name is already added.
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = array();
        $this->addParameters($parameters);

        return $this;
    }

    /**
     * Add an array of parameters to the entity.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator[] $parameters The Parameters to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the a parameter with the same name is already added.
     */
    public function addParameters(array $parameters)
    {
        foreach ($parameters as $param) {
            $this->addParameter($param);
        }

        return $this;
    }

    /**
     * Add a parameter to the entity.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator $parameter The parameter to add.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the a parameter with the same name is already added.
     */
    public function addParameter(ParameterGenerator $parameter)
    {
        $name = $parameter->getName();
        if (isset($this->parameters[$name]) === true) {
            throw new InvalidArgumentException('A parameter with the name "' . $name . '" is already added.');
        }

        $this->parameters[$name] = $parameter;

        return $this;
    }

    /**
     * Get the parameters of the entity.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
