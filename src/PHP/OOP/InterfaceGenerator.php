<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;
use Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\Reflection\ReflectionClass;

/**
 * Class to generate an interface.
 */
class InterfaceGenerator extends AbstractObject
{

    use ObjectConstantsTrait;
    use ObjectMethodsTrait;

    /**
     * Array of interfaces that are extended.
     *
     * @var array
     */
    protected $extends = array();

    /**
     * Constructor.
     *
     * @param string $name The name of the interface.
     * @param string $namespace The namespace for the class.
     */
    public function __construct($name, $namespace = null)
    {
        $this->setName($name);
        $this->setNamespace($namespace);
    }

    /**
     * Create a new interface from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionClass $reflectionClass The reflection of the interface.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the reflected class is not an interface.
     */
    public static function fromReflection(ReflectionClass $reflectionClass)
    {
        if ($reflectionClass->isInterface() !== true) {
            throw new InvalidArgumentException('The reflected class must be an interface.');
        }

        $ig = new static($reflectionClass->getShortName(), $reflectionClass->getNamespaceName());
        if ($reflectionClass->getReflectionDocComment()->isEmpty() !== true) {
            $ig->setDocumentation(DocCommentGenerator::fromReflection($reflectionClass->getReflectionDocComment()));
        }

        $extends = array();
        foreach ($reflectionClass->getOwnInterfaces() as $interface) {
            if ($interface->getNamespaceName() === $ig->getNamespace()) {
                $extends[] = $interface->getShortName();
            } else {
                $extends[] = '\\' . $interface->getName();
            }
        }
        $ig->setExtends($extends);

        foreach ($reflectionClass->getOwnConstants() as $constant) {
            $ig->addConstant(ClassConstantGenerator::fromReflection($constant));
        }

        foreach ($reflectionClass->getOwnMethods() as $method) {
            $method = MethodGenerator::fromReflection($method);
            $method->setAbstract(false);
            $ig->addMethod($method);
        }

        return $ig;
    }

    /**
     * Add an extended interface.
     *
     * @param string $interfaceName The name of the interface that is extended.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the name is not valid.
     */
    public function addExtend($interfaceName)
    {
        if ($this->isObjectNameValid($interfaceName) !== true) {
            throw new InvalidArgumentException('The name of the extended interface is not valid.');
        }

        $this->extends[$interfaceName] = $interfaceName;

        return $this;
    }

    /**
     * Add array of extended interfaces.
     *
     * @param array $interfacesNames The name of the interfaces that are extended.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator
     */
    public function addExtends(array $interfacesNames)
    {
        foreach ($interfacesNames as $interfaceName) {
            $this->addExtend($interfaceName);
        }

        return $this;
    }

    /**
     * Set the interfaces that are extended.
     *
     * @param array $interfacesNames The name of the interfaces that are extended.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\InterfaceGenerator
     */
    public function setExtends(array $interfacesNames)
    {
        $this->extends = array();
        $this->addExtends($interfacesNames);

        return $this;
    }

    /**
     * Get the extended interfaces.
     *
     * @return array
     */
    public function getExtends()
    {
        return $this->extends;
    }

    /**
     * Generate the code.
     *
     * @return string
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException If the methods are not public.
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

        $line = $indentation . 'interface ' . $this->name;
        if (count($this->extends) > 0) {
            $line .= ' extends ' . implode(', ', $this->extends);
        }

        $code[] = $line . ' {';

        // Interface constants.
        $interfaceElements = $this->generateConstantsLines();

        // Interface methods.
        foreach ($this->methods as $method) {
            if ($method->getVisibility() !== Modifiers::VISIBILITY_PUBLIC) {
                throw new RuntimeException('The visibility of methods must be public.');
            }

            $method->setIndentationString($this->getIndentationString());
            $method->setIndentationLevel($this->getIndentationLevel() + 1);

            $doc = $method->generateDocumentation();
            if ($doc !== null) {
                $interfaceElements[] = $doc;
            }

            $interfaceElements[] = $method->generateSignature() . ';';
            $interfaceElements[] = null;
        }

        if (count($interfaceElements) > 0) {
            $code[] = null;
            $code = array_merge($code, $interfaceElements);
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
