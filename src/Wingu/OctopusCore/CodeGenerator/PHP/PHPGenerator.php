<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\AbstractGenerator;

/**
 * Abstract class for all PHP releated code generators.
 */
abstract class PHPGenerator extends AbstractGenerator {

    /**
     * Regular expression for a valid entity name.
     *
     * @var string
     */
    const PATTERN_VALIDATE_NAME = '/^[a-z_]+[a-z0-9_]*$/i';

    /**
     * Regular expression for a valid namespace name.
     *
     * @var string
     */
    const PATTERN_VALIDATE_NAMESPACE_NAME = '/^(\\\){0,1}[a-z_]+[a-z0-9_]*(\\\[a-z_]+[a-z0-9_]*)*$/i';

    /**
     * Check if a namespace is valid.
     *
     * @param string $namespace The namespace to validate.
     * @return boolean
     */
    protected function isNamespaceValid($namespace) {
        if ($namespace === '\\') {
            return true;
        }

        if (strpos($namespace, '\\') === 0) {
            $namespace = substr($namespace, 1);
        }

        $parts = explode('\\', $namespace);
        foreach ($parts as $part) {
            $match = preg_match(self::PATTERN_VALIDATE_NAMESPACE_NAME, $part);
            if ($match === false || $match <= 0 || ReservedKeywords::isReservedKeyword($part) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the name of an object is valid.
     *
     * @param string $name The name of the object to check.
     * @return boolean
     */
    protected function isObjectNameValid($name) {
        return $this->isNamespaceValid($name);
    }

    /**
     * Check if an entity (variable, class, etc.) name is valid.
     *
     * @param string $name The name to check.
     * @return boolean
     */
    protected function isEntityNameValid($name) {
        if (is_string($name) === false) {
            return false;
        }

        $match = preg_match(self::PATTERN_VALIDATE_NAME, $name);
        if ($name === '' || $match === false || $match <= 0 || ReservedKeywords::isReservedKeyword($name) !== false) {
            return false;
        } else {
            return true;
        }
    }
}