<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Abstract class for generating entities (variables, functions, classes, etc).
 */
abstract class AbstractEntityGenerator extends PHPGenerator
{

    use DocCommentTrait;

    /**
     * Name of the entity.
     *
     * @var string
     */
    protected $name;

    /**
     * Set the name of the entity.
     *
     * @param string $name The name to set.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\AbstractEntityGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the name is invalid.
     */
    public function setName($name)
    {
        if ($this->isNameValid($name) !== true) {
            throw new InvalidArgumentException('The name is not valid.');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Get the name of the member.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Check if the name is valid.
     *
     * @param string $name The name to check.
     * @return boolean
     */
    protected function isNameValid($name)
    {
        return $this->isEntityNameValid($name);
    }

    /**
     * Extract the short name from the a fully qualified name.
     *
     * @param string $name The name from which to extract.
     * @return string
     */
    public static function extractShortNameFromFullyQualifiedName($name)
    {
        if (($pos = strrpos($name, '\\')) !== false) {
            return substr($name, $pos + 1);
        } else {
            return $name;
        }
    }
}
