<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;
use Wingu\OctopusCore\Reflection\ReflectionParameter;

/**
 * PHP function / method parameter generator.
 */
class ParameterGenerator extends AbstractEntityGenerator
{

    /**
     * The parameter default value.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator
     */
    protected $defaultValue;

    /**
     * The name of the constant if the default value is a constant.
     *
     * @var string
     */
    protected $defaultValueConstantName;

    /**
     * The parameter type.
     *
     * @var string
     */
    protected $type;

    /**
     * Flag if the parameter is passed by reference.
     *
     * @var boolean
     */
    protected $passByReference = false;

    /**
     * Constructor.
     *
     * @param string $name The name of the parameter.
     * @param mixed $defaultValue The default value of the parameter.
     * @param mixed $type The type of the parameter.
     * @param boolean $passByReference Flag if the parameter is passed by reference.
     */
    public function __construct($name, $defaultValue = null, $type = null, $passByReference = false)
    {
        $this->setName($name);

        if ($defaultValue !== null) {
            $this->setDefaultValue($defaultValue);
        }

        if ($type === null) {
            $this->detectParameterType();
        } else {
            $this->setType($type);
        }

        $this->setPassByReference($passByReference);
    }

    /**
     * Create a new parameter from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionParameter $reflectionParameter The reflection of a parameter.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator
     */
    public static function fromReflection(ReflectionParameter $reflectionParameter)
    {
        $param = new static($reflectionParameter->getName());

        if ($reflectionParameter->isArray() === true) {
            $param->setType('array');
        } else {
            $typeClass = $reflectionParameter->getClass();
            if ($typeClass !== null) {
                $param->setType('\\' . $typeClass->getName());
            }
        }

        if ($reflectionParameter->isOptional() === true) {
            $param->setDefaultValue($reflectionParameter->getDefaultValue());

            if ($reflectionParameter->isDefaultValueConstant() === true) {
                $defaultValueConstantName = $reflectionParameter->getDefaultValueConstantName();

                $declaringFunction = $reflectionParameter->getDeclaringFunction();
                if ($declaringFunction instanceof \ReflectionMethod) {
                    $ns = $declaringFunction->getDeclaringClass()->getNamespaceName();
                } else {
                    $ns = $declaringFunction->getNamespaceName();
                }

                if ($ns !== '' && strpos($defaultValueConstantName, $ns) === 0) {
                    $defaultValueConstantName = substr($defaultValueConstantName, strlen($ns) + 1);
                } elseif (strpos($defaultValueConstantName, '\\') !== false) {
                    $defaultValueConstantName = '\\' . $defaultValueConstantName;
                }

                $param->setDefaultValueConstantName($defaultValueConstantName);
            }
        }

        $param->setPassByReference($reflectionParameter->isPassedByReference());

        return $param;
    }

    /**
     * Check if the name is valid.
     *
     * @param string $name The name to check.
     * @return boolean
     */
    protected function isNameValid($name)
    {
        if (is_string($name) === false) {
            return false;
        }

        $match = preg_match(self::PATTERN_VALIDATE_NAME, $name);
        if ($name === '' || $match === false || $match <= 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if the value can be a default value for a parameter.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator $value The value to check.
     * @return boolean
     */
    protected function isValidParameterDefaultValue(ValueGenerator $value)
    {
        return is_array($value->getValue()) || $value->isValidConstantType();
    }

    /**
     * Set the default value of the parameter.
     *
     * @param mixed $value The default value.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the value is not valid.
     */
    public function setDefaultValue($value)
    {
        if (($value instanceof ValueGenerator) === false) {
            $value = new ValueGenerator($value);
        }

        if ($this->isValidParameterDefaultValue($value) !== true) {
            throw new InvalidArgumentException('Parameter default value is not valid.');
        }

        $this->defaultValue = $value;

        return $this;
    }

    /**
     * Get the default value of the parameter.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Check if the default value is actually a constant.
     *
     * @return boolean
     */
    public function isDefaultValueConstant()
    {
        return $this->defaultValueConstantName !== null;
    }

    /**
     * Set the name of the constant of the default value.
     *
     * @param string $name The name of the constant.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the name is not valid.
     */
    public function setDefaultValueConstantName($name)
    {
        if (is_string($name) !== true || strlen($name) === 0 || preg_match('/\s/', $name) > 0) {
            throw new InvalidArgumentException('Constant name is not valid.');
        }

        $this->defaultValueConstantName = $name;
        return $this;
    }

    /**
     * Get the constant name if the value is actually a constant.
     *
     * @return string
     */
    public function getDefaultValueConstantName()
    {
        return $this->defaultValueConstantName;
    }

    /**
     * Set the type of the parameter.
     *
     * @param string $type The type of the parameter.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the type is not valid.
     */
    public function setType($type)
    {
        if ($type === null || $type === 'array') {
            $this->type = $type;
            return $this;
        }

        if ($this->isObjectNameValid($type) !== true) {
            throw new InvalidArgumentException('Parameter type is not valid.');
        }

        $this->type = $type;
        return $this;
    }

    /**
     * Get the data type of the parameter from a value.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator
     */
    protected function detectParameterType()
    {
        $this->setType(null);

        if ($this->defaultValue !== null) {
            $value = $this->defaultValue->getValue();
            if (is_array($value) === true) {
                $this->setType('array');
            }
        }

        return $this;
    }

    /**
     * Get the type of the parameter.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set parameter is passed by reference.
     *
     * @param boolean $passByReference Flag if the parameter is passed by reference.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator
     */
    public function setPassByReference($passByReference)
    {
        $this->passByReference = (boolean)$passByReference;
        return $this;
    }

    /**
     * Check if the parameter is passed by reference or not.
     *
     * @return boolean
     */
    public function isPassByReference()
    {
        return $this->passByReference;
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate()
    {
        $code = '';

        if ($this->type !== null) {
            $code .= $this->type . ' ';
        }

        if ($this->passByReference === true) {
            $code .= '&';
        }

        $code .= '$' . $this->name;

        if ($this->defaultValue !== null) {
            if ($this->isDefaultValueConstant() === true) {
                $code .= ' = ' . $this->getDefaultValueConstantName();
            } else {
                $this->defaultValue->setOutputMode(ValueGenerator::OUTPUT_SINGLE_LINE);
                $code .= ' = ' . $this->defaultValue;
            }
        }

        return $code;
    }
}