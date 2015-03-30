<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;
use Wingu\OctopusCore\CodeGenerator\PHP\AbstractEntityGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator;
use Wingu\OctopusCore\Reflection\ReflectionConstant;

/**
 * PHP class constant generator.
 */
class ClassConstantGenerator extends AbstractEntityGenerator
{

    /**
     * The value of the constant.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param string $name The name of the constant.
     * @param mixed $value The value of the constant.
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator $documentation The documentation to set.
     */
    public function __construct($name, $value, DocCommentGenerator $documentation = null)
    {
        $this->setName($name);
        $this->setValue($value);
        if ($documentation !== null) {
            $this->setDocumentation($documentation);
        }
    }

    /**
     * Create a new class constant generator from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionConstant $reflectionConstant The reflection of a class constant.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator
     */
    public static function fromReflection(ReflectionConstant $reflectionConstant)
    {
        $cc = new static($reflectionConstant->getName(), $reflectionConstant->getValue());

        if ($reflectionConstant->getReflectionDocComment()->isEmpty() !== true) {
            $cc->setDocumentation(DocCommentGenerator::fromReflection($reflectionConstant->getReflectionDocComment()));
        }

        return $cc;
    }

    /**
     * Set the value for the constant.
     *
     * @param mixed $value The value for the constant.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassConstantGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the value is not valid.
     */
    public function setValue($value)
    {
        if (($value instanceof ValueGenerator) !== true) {
            $value = new ValueGenerator($value);
        }

        if ($value->isValidConstantType() !== true) {
            throw new InvalidArgumentException('Constant value is not valid (' . gettype($value->getValue()) . ' type given).');
        }

        $this->value = $value;

        return $this;
    }

    /**
     * Generate the class constant code.
     *
     * @return string
     */
    public function generate()
    {
        $code = array();

        $doc = $this->generateDocumentation();
        if ($doc !== null) {
            $code[] = $doc;
        }

        $code[] = $this->getIndentation() . 'const ' . $this->name . ' = ' . $this->value->generate() . ';';

        return implode($this->getLineFeed(), $code);
    }
}
