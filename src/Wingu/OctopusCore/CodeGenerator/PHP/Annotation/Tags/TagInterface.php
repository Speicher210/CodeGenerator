<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

use Wingu\OctopusCore\CodeGenerator\GeneratorInterface;
use Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface as ReflectionTagInterface;

/**
 * Interface for annotations.
 */
interface TagInterface extends GeneratorInterface {

    /**
     * Create a new tag from reflection.
     *
     * @param \Wingu\OctopusCore\Reflection\Annotation\Tags\TagInterface $reflectionTag The tag reflection.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\TagInterface
     */
    public static function fromReflection(ReflectionTagInterface $reflectionTag);

    /**
     * Get the tag name of the annotation.
     *
     * @return string
     */
    public function getTagName();

    /**
     * Get the description of the annotation.
     *
     * @return string
     */
    public function getDescription();
}