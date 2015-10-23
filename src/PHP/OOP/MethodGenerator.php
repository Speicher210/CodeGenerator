<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException;
use Wingu\OctopusCore\CodeGenerator\PHP\AbstractEntityGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\BodyTrait;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\ParameterTrait;
use Wingu\OctopusCore\Reflection\ReflectionMethod;

/**
 * Class to generate object methods.
 */
class MethodGenerator extends AbstractEntityGenerator
{

    use ModifiersBaseTrait;
    use ModifiersAbstractTrait;
    use ModifiersFinalTrait;
    use ModifiersVisibilityTrait;
    use ModifiersStaticTrait;
    use ParameterTrait;
    use BodyTrait;

    /**
     * Constructor.
     *
     * @param string $name The name of the function.
     * @param string $body The body of the function.
     * @param array $parameters The functions parameters.
     */
    public function __construct($name, $body = null, array $parameters = array())
    {
        $this->setName($name);

        if ($body !== null) {
            $this->setBody($body);
        }

        $this->addParameters($parameters);
    }

    /**
     * Create a new method from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionMethod $reflectionMethod The reflection of the method.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\MethodGenerator
     */
    public static function fromReflection(ReflectionMethod $reflectionMethod)
    {
        $method = new static($reflectionMethod->getName());
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $method->addParameter(ParameterGenerator::fromReflection($parameter));
        }

        $method->setFinal($reflectionMethod->isFinal());
        $method->setAbstract($reflectionMethod->isAbstract());

        if ($reflectionMethod->isStatic() === true) {
            $method->setStatic(true);
        }

        if ($reflectionMethod->isPrivate() === true) {
            $method->setVisibility(Modifiers::VISIBILITY_PRIVATE);
        } elseif ($reflectionMethod->isProtected() === true) {
            $method->setVisibility(Modifiers::VISIBILITY_PROTECTED);
        } else {
            $method->setVisibility(Modifiers::VISIBILITY_PUBLIC);
        }

        if ($reflectionMethod->getReflectionDocComment()->isEmpty() !== true) {
            $method->setDocumentation(
                DocCommentGenerator::fromReflection($reflectionMethod->getReflectionDocComment())
            );
        }

        if ($reflectionMethod->isAbstract() !== true) {
            $body = trim($reflectionMethod->getBody());
            if ($body !== '') {
                $method->setBody($body);
            }
        }

        return $method;
    }

    /**
     * Generate the signature of the method.
     *
     * @return string
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException If the method is abstract and final or abstract and private.
     */
    public function generateSignature()
    {
        if ($this->isFinal() === true && $this->isAbstract() === true) {
            throw new RuntimeException('A method can not be "abstract" and "final".');
        }

        if ($this->isAbstract() === true && $this->getVisibility() === Modifiers::VISIBILITY_PRIVATE) {
            throw new RuntimeException('A method can not be "abstract" and "private".');
        }

        $signature = $this->getIndentation();
        if ($this->isFinal() === true) {
            $signature .= 'final ';
        } elseif ($this->isAbstract()) {
            $signature .= 'abstract ';
        }

        $signature .= $this->getVisibility();

        if ($this->isStatic() === true) {
            $signature .= ' static';
        }

        return $signature . ' function ' . $this->name . '(' . implode(', ', $this->parameters) . ')';
    }

    /**
     * Generate the code.
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

        if ($this->isAbstract() === true) {
            $code[] = $this->generateSignature() . ';';
        } else {
            $code[] = $this->generateSignature();
            $code[] = $this->getIndentation() . $this->generateBody();
        }

        return implode($this->getLineFeed(), $code);
    }
}
