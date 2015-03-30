<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\Reflection\ReflectionClass;

/**
 * Class to generate a trait.
 */
class TraitGenerator extends AbstractObject
{

    use ModifiersBaseTrait;
    use ObjectUsesTrait;
    use ObjectPropertiesTrait;
    use ObjectMethodsTrait;

    /**
     * Constructor.
     *
     * @param string $name The name of the trait.
     * @param string $namespace The namespace for the trait.
     */
    public function __construct($name, $namespace = null)
    {
        $this->setName($name);
        $this->setNamespace($namespace);
    }

    /**
     * Create a new trait from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionClass $reflectionClass The reflection of the trait.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\TraitGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the reflected class is not a trait.
     */
    public static function fromReflection(ReflectionClass $reflectionClass)
    {
        if ($reflectionClass->isTrait() !== true) {
            throw new InvalidArgumentException('The reflected class must be a trait.');
        }

        $tg = new static($reflectionClass->getShortName(), $reflectionClass->getNamespaceName());

        // Documentation.
        if ($reflectionClass->getReflectionDocComment()->isEmpty() !== true) {
            $tg->setDocumentation(DocCommentGenerator::fromReflection($reflectionClass->getReflectionDocComment()));
        }

        // Uses.
        foreach ($reflectionClass->getUses() as $use) {
            $use = UseTraitGenerator::fromReflection($use);
            if ($tg->getNamespace() !== null && strpos($use->getTraitClass(), $tg->getNamespace()) === 0) {
                $use->setTraitClass(substr($use->getTraitClass(), strlen($tg->getNamespace()) + 1));
            }

            $tg->addTraitUse($use);
        }

        // Properties.
        foreach ($reflectionClass->getOwnProperties() as $property) {
            $tg->addProperty(PropertyGenerator::fromReflection($property));
        }

        // Methods.
        foreach ($reflectionClass->getOwnMethods() as $method) {
            $tg->addMethod(MethodGenerator::fromReflection($method));
        }

        return $tg;
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

        $code = array_merge($code, $this->renderUsesLines());

        $indentation = $this->getIndentation();

        $doc = $this->generateDocumentation();
        if ($doc !== null) {
            $code[] = $doc;
        }

        $code[] = $indentation . 'trait ' . $this->name . ' {';

        // Class uses.
        $traitElements = $this->generateTraitUsesLines();

        // Class properties.
        $traitElements = array_merge($traitElements, $this->generatePropertiesLines());

        // Class methods.
        $traitElements = array_merge($traitElements, $this->generateMethodsLines());

        if (count($traitElements) > 0) {
            $code[] = null;
            $code = array_merge($code, $traitElements);
            // Remove last empty line.
            array_pop($code);
        }

        $code[] = $indentation . '}';

        if ($this->namespace !== null) {
            // Set back the indentation.
            $this->addIndentationLevel(-1);

            $code[] = $this->getIndentation() . '}';
        }

        return implode($this->getLineFeed(), $code);
    }
}