<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;
use Wingu\OctopusCore\CodeGenerator\PHP\PHPGenerator;
use Wingu\OctopusCore\Reflection\ReflectionClassUse;

/**
 * A class to generate the "use" of traits in an object.
 */
class UseTraitGenerator extends PHPGenerator
{

    /**
     * The trait.
     *
     * @var string
     */
    protected $traitClass;

    /**
     * The conflict resolutions.
     *
     * @var array
     */
    protected $conflictsResolutions = array();

    /**
     * Constructor.
     *
     * @param string $traitClass The trait name to use.
     * @param array $conflictsResolutions Array where each item is a conflict resolution.
     */
    public function __construct($traitClass, array $conflictsResolutions = array())
    {
        $this->setTraitClass($traitClass);
        $this->setConflictsResolutions($conflictsResolutions);
    }

    /**
     * Create a new use statement from reflection.
     *
     * @param ReflectionClassUse $reflectionClassUse The reflection of the use.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator
     */
    public static function fromReflection(ReflectionClassUse $reflectionClassUse)
    {
        return new static($reflectionClassUse->getName(), $reflectionClassUse->getConflictResolutions());
    }

    /**
     * Set the trait name to use.
     *
     * @param string $traitClass The trait class to use.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the use is not valid.
     */
    public function setTraitClass($traitClass)
    {
        if ($this->isObjectNameValid($traitClass) !== true) {
            throw new InvalidArgumentException('The name of the trait to use is not valid.');
        }

        $this->traitClass = $traitClass;

        return $this;
    }

    /**
     * Get the trait class.
     *
     * @return string
     */
    public function getTraitClass()
    {
        return $this->traitClass;
    }

    /**
     * Set the conflict resolutions.
     *
     * @param array $conflictsResolutions Array where each item is a conflict resolution.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator
     */
    public function setConflictsResolutions(array $conflictsResolutions)
    {
        $this->conflictsResolutions = $conflictsResolutions;

        return $this;
    }

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate()
    {
        $indentation = $this->getIndentation();

        $code = $indentation . 'use ' . $this->traitClass;
        if (count($this->conflictsResolutions) > 0) {
            $conflictIndentation = $indentation . $this->indentationString;
            $code .= ' {' . $this->lineFeed;
            foreach ($this->conflictsResolutions as $conflict) {
                $code .= $conflictIndentation . rtrim($conflict, ';') . ';' . $this->lineFeed;
            }
            $code .= $indentation . '}';
        } else {
            $code .= ';';
        }

        return $code;
    }
}
