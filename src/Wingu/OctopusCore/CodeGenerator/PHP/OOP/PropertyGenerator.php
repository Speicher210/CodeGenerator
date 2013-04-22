<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\PHP\AbstractEntityGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator;
use Wingu\OctopusCore\Reflection\ReflectionProperty;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Class for generating object properties.
 */
class PropertyGenerator extends AbstractEntityGenerator {

    use ModifiersBaseTrait;
    use ModifiersVisibilityTrait;
    use ModifiersStaticTrait;

    /**
     * The property default value.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator
     */
    protected $defaultValue;

    /**
     * Constructor.
     *
     * @param string $name The name of the property.
     * @param mixed $defaultValue The default value for the property.
     * @param integer $modifiers The modfiers for the property.
     */
    public function __construct($name, $defaultValue = null, $modifiers = Modifiers::MODIFIER_PUBLIC) {
        $this->setName($name);
        $this->setDefaultValue($defaultValue);
        $this->setModifiers($modifiers);
    }

    /**
     * Create a new property generator from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionProperty $reflectionConstant The reflection of a class property.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator
     */
    public static function fromReflection(ReflectionProperty $reflectionProperty) {
        $pg = new static($reflectionProperty->getName());
        if ($reflectionProperty->getReflectionDocComment()->isEmpty() !== true) {
            $pg->setDocumentation(DocCommentGenerator::fromReflection($reflectionProperty->getReflectionDocComment()));
        }

        if ($reflectionProperty->isDefault() === true) {
            $defaultValule = $reflectionProperty->getDeclaringClass()->getDefaultProperties()[$reflectionProperty->getName()];
            $pg->setDefaultValue($defaultValule);
        }

        if ($reflectionProperty->isStatic() === true) {
            $pg->setStatic(true);
        }

        if ($reflectionProperty->isPrivate() === true) {
            $pg->setVisibility(Modifiers::VISIBILITY_PRIVATE);
        } elseif ($reflectionProperty->isProtected() === true) {
            $pg->setVisibility(Modifiers::VISIBILITY_PROTECTED);
        } else {
            $pg->setVisibility(Modifiers::VISIBILITY_PUBLIC);
        }

        return $pg;
    }

    /**
     * Check if the name is valid.
     *
     * @param string $name The name to check.
     * @return boolean
     */
    protected function isNameValid($name) {
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
    protected function isValidPropertyDefaultValue(ValueGenerator $value) {
        return is_array($value->getValue()) || $value->isValidConstantType();
    }

    /**
     * Set the default value for the property.
     *
     * @param mixed $value The value for the property.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\PropertyGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the value is not valid.
     */
    public function setDefaultValue($value) {
        if (($value instanceof ValueGenerator) === false) {
            $value = new ValueGenerator($value);
            $value->setOutputMode(ValueGenerator::OUTPUT_SINGLE_LINE);
        }

        if ($this->isValidPropertyDefaultValue($value) !== true) {
            throw new InvalidArgumentException('Parameter default value is not valid.');
        }

        $this->defaultValue = $value;

        return $this;
    }

    /**
     * Get the default value of the property.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator
     */
    public function getDefaultValue() {
        return $this->defaultValue;
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate() {
        $code = array();

        $doc = $this->generateDocumentation();
        if ($doc !== null) {
            $code[] = $doc;
        }

        $propertyCode = $this->getIndentation() . $this->getVisibility() . ' ';
        if ($this->isStatic() === true) {
            $propertyCode .= 'static ';
        }

        $propertyCode .= '$' . $this->name;

        if ($this->defaultValue !== null && $this->defaultValue->getValue() !== null) {
            $propertyCode .= ' = ' . $this->defaultValue . ';';
        } else {
            $propertyCode .= ';';
        }

        $code[] = $propertyCode;

        return implode($this->getLineFeed(), $code);
    }
}