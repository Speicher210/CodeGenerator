<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\Reflection\ReflectionClass;
use Wingu\OctopusCore\CodeGenerator\PHP\DocCommentGenerator;
use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Class to generate a class.
 */
class ClassGenerator extends AbstractObject {

    use ModifiersBaseTrait;
    use ModifiersAbstractTrait;
    use ModifiersFinalTrait;
    use ObjectUsesTrait;
    use ObjectConstantsTrait;
    use ObjectPropertiesTrait;
    use ObjectMethodsTrait;

    /**
     * The name of the class that this generated class extends.
     *
     * @var string
     */
    protected $extends;

    /**
     * Array of interfaces names that this class implements.
     *
     * @var array
     */
    protected $implements;

    /**
     * Constructor.
     *
     * @param string $name The name of the class.
     * @param string $namespace The namespace for the class.
     */
    public function __construct($name, $namespace = null) {
        $this->setName($name);
        $this->setNamespace($namespace);
    }

    /**
     * Create a new class from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\ReflectionClass $reflectionClass The reflection of the class.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ClassGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the reflected class is a trait or interface.
     */
    public static function fromReflection(ReflectionClass $reflectionClass) {
        if ($reflectionClass->isInterface() === true || $reflectionClass->isTrait() === true) {
            throw new InvalidArgumentException('The reflected class can not be a trait or interface.');
        }

        $cg = new static($reflectionClass->getShortName(), $reflectionClass->getNamespaceName());
        $cg->setFinal($reflectionClass->isFinal());
        $cg->setAbstract($reflectionClass->isAbstract());

        // Extension.
        $parentClass = $reflectionClass->getParentClass();
        if ($parentClass !== false) {
            if ($parentClass->getNamespaceName() === $cg->getNamespace()) {
                $extends = $parentClass->getShortName();
            } else {
                $extends = '\\' . $parentClass->getName();
            }
            $cg->setExtends($extends);
        }

        // Interfaces.
        $interfaces = array();
        foreach ($reflectionClass->getOwnInterfaces() as $interface) {
            if ($interface->getNamespaceName() === $cg->getNamespace()) {
                $interfaces[] = $interface->getShortName();
            } else {
                $interfaces[] = '\\' . $interface->getName();
            }
        }
        $cg->setImplements($interfaces);

        // Documentation.
        if ($reflectionClass->getReflectionDocComment()->isEmpty() !== true) {
            $cg->setDocumentation(DocCommentGenerator::fromReflection($reflectionClass->getReflectionDocComment()));
        }

        // Uses.
        foreach ($reflectionClass->getUses() as $use) {
            $use = UseTraitGenerator::fromReflection($use);
            if ($cg->getNamespace() !== null && strpos($use->getTraitClass(), $cg->getNamespace()) === 0) {
                $use->setTraitClass(substr($use->getTraitClass(), strlen($cg->getNamespace())+1));
            }

            $cg->addTraitUse($use);
        }

        // Constants.
        foreach ($reflectionClass->getOwnConstants() as $constant) {
            $cg->addConstant(ClassConstantGenerator::fromReflection($constant));
        }

        // Properties.
        foreach ($reflectionClass->getOwnProperties() as $property) {
            $cg->addProperty(PropertyGenerator::fromReflection($property));
        }

        // Methods.
        foreach ($reflectionClass->getOwnMethods() as $method) {
            $cg->addMethod(MethodGenerator::fromReflection($method));
        }

        return $cg;
    }

    /**
     * Set the name of the class that this class extends.
     *
     * @param string $extends The name of the class that this class extends.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the name is not valid.
     */
    public function setExtends($extends) {
        if ($extends !== null && $this->isObjectNameValid($extends) !== true) {
            throw new InvalidArgumentException('The name of the extended class is not valid.');
        }

        $this->extends = $extends;

        return $this;
    }

    /**
     * Get the class name that this class extends.
     *
     * @return string
     */
    public function getExtends() {
        return $this->extends;
    }

    /**
     * Add an interface that this class implements.
     *
     * @param string $interfaceName The name of the interface that is implemented.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the name is not valid.
     */
    public function addImplement($interfaceName) {
        if ($this->isObjectNameValid($interfaceName) !== true) {
            throw new InvalidArgumentException('The name of the interface is not valid.');
        }

        $this->implements[$interfaceName] = $interfaceName;

        return $this;
    }

    /**
     * Add array of interfaces that this class implements.
     *
     * @param array $interfacesNames The name of the interfaces that are implemented.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator
     */
    public function addImplements(array $interfacesNames) {
        foreach ($interfacesNames as $interfaceName) {
            $this->addImplement($interfaceName);
        }

        return $this;
    }

    /**
     * Set the interfaces that this class implements.
     *
     * @param array $interfacesNames The name of the interfaces that are implemented.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\ClassGenerator
     */
    public function setImplements(array $interfacesNames) {
        $this->implements = array();
        $this->addImplements($interfacesNames);
        return $this;
    }

    /**
     * Get the interfaces that this class implements.
     *
     * @return array
     */
    public function getImplements() {
        return $this->implements;
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate() {
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

        $line = $indentation;
        if ($this->isFinal() === true) {
            $line .= 'final ';
        } elseif ($this->isAbstract() === true) {
            $line .= 'abstract ';
        }

        $line .= 'class ' . $this->name;

        if ($this->extends !== null) {
            $line .= ' extends ' . $this->extends;
        }

        if (count($this->implements) > 0) {
            $line .= ' implements ' . implode(', ', $this->implements);
        }

        $code[] = $line . ' {';
        $code[] = null;

        // Class uses.
        $code = array_merge($code, $this->generateTraitUsesLines());

        // Class constants.
        $code = array_merge($code, $this->generateConstantsLines());

        // Class properties.
        $code = array_merge($code, $this->generatePropertiesLines());

        // Class methods.
        $methods = $this->generateMethodsLines();
        // Remove last empty line.
        array_pop($methods);
        $code = array_merge($code, $methods);

        $code[] = $indentation . '}';

        if ($this->namespace !== null) {
            // Set back the indentation.
            $this->addIndentationLevel(-1);

            $code[] = $this->getIndentation() . '}';
        }

        return implode($this->getLineFeed(), $code);
    }
}