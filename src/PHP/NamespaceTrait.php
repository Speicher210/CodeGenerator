<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Trait for entities that have can have a namespace.
 */
trait NamespaceTrait
{

    /**
     * The namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Set the namespace name.
     *
     * @param string $namespace The name of the namespace.
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the namespace is invalid.
     */
    public function setNamespace($namespace)
    {
        if ($namespace === null || $namespace === '' || $namespace === '\\') {
            $this->namespace = null;
            return;
        }

        if ($namespace[0] === '\\') {
            $namespace = substr($namespace, 1);
        }

        if ($this->isNamespaceValid($namespace) !== true) {
            throw new InvalidArgumentException('Namespace name is not valid.');
        }

        $this->namespace = $namespace;
    }

    /**
     * Get the namespace name.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Extract the namespace from the a fully qualified name.
     *
     * @param string $name The name from which to extract.
     * @return string
     */
    public static function extractNamespaceFromQualifiedName($name)
    {
        if (($pos = strrpos($name, '\\')) !== false) {
            return substr($name, 0, $pos);
        } else {
            return null;
        }
    }
}