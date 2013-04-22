<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

/**
 * Documentation annotation "property" tag generator.
 */
class PropertyTag extends BaseTag {

    const ACCESS_FULL = 'property';

    const ACCESS_READ = 'property-read';

    const ACCESS_WRITE = 'property-write';

    /**
     * The type of the property.
     *
     * @var string
     */
    protected $propertyType;

    /**
     * The name of the property.
     *
     * @var string
     */
    protected $propertyName;

    /**
     * Constructor.
     *
     * @param string $propertyType The name of the property.
     * @param string $propertyName The name of the property.
     * @param string $propertyDescription The description for the property.
     * @param string $access The property access.
     */
    public function __construct($propertyType, $propertyName, $propertyDescription = null, $access = self::ACCESS_FULL) {
        parent::__construct('property', $propertyDescription);

        $this->setType($propertyType);
        $this->setPropertyName($propertyName);
        $this->setAccess($access);
    }

    /**
     * Set the type of the parameter.
     *
     * @param string $type The type of the parameter.
     */
    public function setType($type) {
        $this->propertyType = $type;
    }

    /**
     * Get the property type.
     *
     * @return string
     */
    public function getType() {
        return $this->propertyType;
    }

    /**
     * Set the property name.
     *
     * @param string $name The name of the property.
     */
    public function setPropertyName($name) {
        $this->propertyName = $name;
    }

    /**
     * Get the property name.
     *
     * @return string
     */
    public function getPropertyName() {
        return $this->propertyName;
    }

    /**
     * Set the access of the property.
     *
     * @param string $access The access.
     */
    public function setAccess($access = self::ACCESS_FULL) {
        $this->name = $access;
    }

    /**
     * Get the access of the property.
     *
     * @return string
     */
    public function getAccess() {
        return $this->name;
    }

    /**
     * Generate the annotation tag.
     *
     * @return string
     */
    public function generate() {
        return trim('@' . $this->getAccess() . ' ' . $this->getType() . ' $' . $this->getPropertyName() . ' ' . $this->description);
    }
}
