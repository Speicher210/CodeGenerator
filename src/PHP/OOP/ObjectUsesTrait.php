<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

/**
 * Trait to deal with adding "use" statements of traits for objects.
 */
trait ObjectUsesTrait
{

    /**
     * The trait uses.
     *
     * @var \Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator[]
     */
    protected $traitUses = array();

    /**
     * Add a use.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator $use The use.
     */
    public function addTraitUse(UseTraitGenerator $use)
    {
        $this->traitUses[] = $use;
    }

    /**
     * Add array of uses.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator[] $uses The uses.
     */
    public function addTraitUses(array $uses)
    {
        foreach ($uses as $use) {
            $this->addTraitUse($use);
        }
    }

    /**
     * Set the uses.
     *
     * @param \Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator[] $uses The uses to set.
     */
    public function setTraitUses(array $uses)
    {
        $this->traitUses = array();
        $this->addTraitUses($uses);
    }

    /**
     * Get the defined uses.
     *
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\OOP\UseTraitGenerator[]
     */
    public function getTraitUses()
    {
        return $this->traitUses;
    }

    /**
     * Generate the uses lines.
     *
     * @return array
     */
    protected function generateTraitUsesLines()
    {
        $code = array();
        foreach ($this->traitUses as $use) {
            $use->setIndentationString($this->getIndentationString());
            $use->setIndentationLevel($this->getIndentationLevel() + 1);
            $code[] = $use->generate();
            $code[] = null;
        }

        return $code;
    }
}
