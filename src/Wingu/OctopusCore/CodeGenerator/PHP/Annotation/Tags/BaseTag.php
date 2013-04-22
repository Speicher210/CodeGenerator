<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\AbstractGenerator;
use Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface as ReflectionTagInterface;

/**
 * Documentation annotation base tag generator.
 */
class BaseTag extends AbstractGenerator implements TagInterface {

    /**
     * The annotation tag name.
     *
     * @var string
     */
    protected $name;

    /**
     * The tag description / detail.
     *
     * @var string
     */
    protected $description;

    /**
     * Constructor.
     *
     * @param string $name The annotation tag name.
     * @param string $description The tag description / detail.
     */
    public function __construct($name, $description = null) {
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Create a new tag from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface $reflectionTag The tag reflection.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface
     */
    public static function fromReflection(ReflectionTagInterface $reflectionTag) {
        return new static($reflectionTag->getTagName(), $reflectionTag->getDescription());
    }

    /**
     * Get the tag.
     *
     * @return string
     */
    public function getTagName() {
        return $this->name;
    }

    /**
     * Get the description of the annotation tag.
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Generate the description part of the annotation tag.
     *
     * @return string
     */
    protected function generateDescriptionPart() {
        return $this->description;
    }

    /**
     * Generate the annotation tag.
     *
     * @return string
     */
    public function generate() {
        return trim('@' . $this->name . ' ' . $this->generateDescriptionPart());
    }
}