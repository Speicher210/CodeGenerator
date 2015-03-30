<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Trait to deal with adding properties generators.
 */
trait ObjectPropertiesTrait
{

    /**
     * Properties.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator[]
     */
    protected $properties = array();

    /**
     * Add a property.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator $property The property to add.
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the property name already exists.
     */
    public function addProperty(PropertyGenerator $property)
    {
        $name = $property->getName();
        if (isset($this->properties[$name]) === true) {
            throw new InvalidArgumentException('Property name "' . $name . '" already added.');
        }

        $this->properties[$name] = $property;
    }

    /**
     * Add an array of properties.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator[] $properties The properties to add.
     */
    public function addProperties(array $properties)
    {
        foreach ($properties as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * Set the properties.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator[] $properties The properties to set.
     */
    public function setProperties(array $properties = array())
    {
        $this->properties = array();
        $this->addProperties($properties);
    }

    /**
     * Get the defined properties.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\PropertyGenerator[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Check if a property exists.
     *
     * @param string $name The name of the property to check.
     * @return boolean
     */
    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * Generate the properties lines.
     *
     * @return array
     */
    protected function generatePropertiesLines()
    {
        $code = array();
        foreach ($this->properties as $property) {
            $property->setIndentationString($this->getIndentationString());
            $property->setIndentationLevel($this->getIndentationLevel() + 1);
            $code[] = $property->generate();
            $code[] = null;
        }

        return $code;
    }
}
