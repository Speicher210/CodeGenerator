<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Trait to deal with adding methods generators.
 */
trait ObjectMethodsTrait
{

    /**
     * Methods.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator[]
     */
    protected $methods = array();

    /**
     * Add a method.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator $method The method to add.
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the method name already exists.
     */
    public function addMethod(MethodGenerator $method)
    {
        $name = $method->getName();
        if (isset($this->methods[$name]) === true) {
            throw new InvalidArgumentException('Method name "' . $name . '" already added.');
        }

        $this->methods[$name] = $method;
    }

    /**
     * Add an array of methods.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator[] $methods The methods to add.
     */
    public function addMethods(array $methods)
    {
        foreach ($methods as $method) {
            $this->addMethod($method);
        }
    }

    /**
     * Set the methods.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator[] $methods The methods to set.
     */
    public function setMethods(array $methods = array())
    {
        $this->methods = array();
        $this->addMethods($methods);
    }

    /**
     * Get the defined methods.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Get a method definition.
     *
     * @param string $name The name of the method to get.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator
     * @throws InvalidArgumentException If the method is not found.
     */
    public function getMethod($name)
    {
        if ($this->hasMethod($name) === true) {
            return $this->methods[$name];
        } else {
            throw new InvalidArgumentException('Method "' . $name . '" is not defined.');
        }
    }

    /**
     * Check if a method exists.
     *
     * @param string $name The name of the method to check.
     * @return boolean
     */
    public function hasMethod($name)
    {
        return isset($this->methods[$name]);
    }

    /**
     * Generate the methods lines.
     *
     * @return array
     */
    protected function generateMethodsLines()
    {
        $code = array();
        foreach ($this->methods as $method) {
            $method->setIndentationString($this->getIndentationString());
            $method->setIndentationLevel($this->getIndentationLevel() + 1);
            $code[] = $method->generate();
            $code[] = null;
        }

        return $code;
    }
}
