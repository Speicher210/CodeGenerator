<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

/**
 * Trait to deal with the "final" modifier.
 */
trait ModifiersFinalTrait
{

    /**
     * Set the entity "final" modifier.
     *
     * @param boolean $final Flag if the entity is final or not.
     */
    public function setFinal($final)
    {
        if ($final === true) {
            $this->addModifier(Modifiers::MODIFIER_FINAL);
        } else {
            $this->removeModifier(Modifiers::MODIFIER_FINAL);
        }
    }

    /**
     * Check if the entity is final or not.
     *
     * @return boolean
     */
    public function isFinal()
    {
        return (bool)($this->modifiers & Modifiers::MODIFIER_FINAL);
    }
}
