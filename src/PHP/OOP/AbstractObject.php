<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\PHP\AbstractEntityGenerator;
use Wingu\OctopusCore\CodeGenerator\PHP\GlobalUseTrait;
use Wingu\OctopusCore\CodeGenerator\PHP\NamespaceTrait;

/**
 * Abstract implementation for OOP elements like classes, interfaces, traits.
 */
abstract class AbstractObject extends AbstractEntityGenerator
{

    use NamespaceTrait;
    use GlobalUseTrait;

    /**
     * Check if the name is valid.
     *
     * @param string $name The name to check.
     * @return boolean
     */
    protected function isNameValid($name)
    {
        return $this->isObjectNameValid($name);
    }

    /**
     * Get the qualified name.
     *
     * This includes the namespace (if any).
     *
     * @return string
     */
    public function getQualifiedName()
    {
        if ($this->namespace !== null) {
            return $this->namespace . '\\' . $this->name;
        } else {
            return $this->name;
        }
    }

    /**
     * Get the fully qualified name.
     *
     * This includes the namespace (if any) and adds the namespace separator at the beginning.
     *
     * @return string
     */
    public function getFullyQualifiedName()
    {
        return '\\' . $this->getQualifiedName();
    }
}
