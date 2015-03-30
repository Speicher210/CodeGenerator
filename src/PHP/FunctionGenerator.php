<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\Reflection\ReflectionFunction;

/**
 * Class for generating a function.
 */
class FunctionGenerator extends AbstractEntityGenerator
{

    use NamespaceTrait;
    use ParameterTrait;
    use BodyTrait;

    /**
     * Constructor.
     *
     * @param string $name The name of the function.
     * @param string $body The body of the function.
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\ParameterGenerator[] $parameters The functions parameters.
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
     * Create a new function from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionFunction $reflectionFunction The reflection of the function.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FunctionGenerator
     */
    public static function fromReflection(ReflectionFunction $reflectionFunction)
    {
        $name = self::extractShortNameFromFullyQualifiedName($reflectionFunction->getName());
        $namespace = self::extractNamespaceFromQualifiedName($reflectionFunction->getName());

        $function = new static($name, trim($reflectionFunction->getBody()));
        $function->setNamespace($namespace);
        foreach ($reflectionFunction->getParameters() as $parameter) {
            $function->addParameter(ParameterGenerator::fromReflection($parameter));
        }

        if ($reflectionFunction->getReflectionDocComment()->isEmpty() !== true) {
            $function->setDocumentation(
                DocCommentGenerator::fromReflection($reflectionFunction->getReflectionDocComment())
            );
        }

        return $function;
    }

    /**
     * Generate the signature of the function.
     *
     * @return string
     */
    public function generateSignature()
    {
        return $this->getIndentation() . 'function ' . $this->name . '(' . implode(', ', $this->parameters) . ')';
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate()
    {
        $code = array();

        if ($this->namespace !== null) {
            $code[] = $this->getIndentation() . 'namespace ' . $this->namespace . ' {';
            $code[] = null;

            // Temporarily add one more level of indentation.
            $this->addIndentationLevel(1);
        }

        $doc = $this->generateDocumentation();
        if ($doc !== null) {
            $code[] = $doc;
        }

        $code[] = $this->generateSignature() . ' ' . $this->generateBody();

        if ($this->namespace !== null) {
            // Set back the indentation.
            $this->addIndentationLevel(-1);

            $code[] = $this->getIndentation() . '}';
        }

        return implode($this->getLineFeed(), $code);
    }
}
