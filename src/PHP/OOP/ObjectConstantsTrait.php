<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Trait to deal with adding constants generators.
 */
trait ObjectConstantsTrait
{

    /**
     * Constants.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator[]
     */
    protected $constants = array();

    /**
     * Add a constant.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator $constant The constant to add.
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the constant name already exists.
     */
    public function addConstant(ClassConstantGenerator $constant)
    {
        $name = $constant->getName();
        if (isset($this->constants[$name]) === true) {
            throw new InvalidArgumentException('Constant name "' . $name . '" already added.');
        }

        $this->constants[$name] = $constant;
    }

    /**
     * Add an array of constants.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator[] $constants The constants to add.
     */
    public function addConstants(array $constants)
    {
        foreach ($constants as $constant) {
            $this->addConstant($constant);
        }
    }

    /**
     * Set the constants.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator[] $constants The constants to set.
     */
    public function setConstants(array $constants)
    {
        $this->constants = array();
        $this->addConstants($constants);
    }

    /**
     * Get the defined constants.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator[]
     */
    public function getConstants()
    {
        return $this->constants;
    }

    /**
     * Check if a constant exists.
     *
     * @param string $name The name of the constant to check.
     * @return boolean
     */
    public function hasConstant($name)
    {
        return isset($this->constants[$name]);
    }

    /**
     * Generate the constants lines.
     *
     * @return array
     */
    protected function generateConstantsLines()
    {
        $code = array();
        foreach ($this->constants as $constant) {
            $constant->setIndentationString($this->getIndentationString());
            $constant->setIndentationLevel($this->getIndentationLevel() + 1);
            $code[] = $constant->generate();
            $code[] = null;
        }

        return $code;
    }
}