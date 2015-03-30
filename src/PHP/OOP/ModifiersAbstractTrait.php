<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

/**
 * Trait to deal with the "abstract" modifier.
 */
trait ModifiersAbstractTrait
{

    /**
     * Set the entity abstract.
     *
     * @param boolean $abstract Flag if the entity is abstract or not.
     */
    public function setAbstract($abstract)
    {
        if ($abstract === true) {
            $this->addModifier(Modifiers::MODIFIER_ABSTRACT);
        } else {
            $this->removeModifier(Modifiers::MODIFIER_ABSTRACT);
        }
    }

    /**
     * Check if the entity is abstract or not.
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return (bool)($this->modifiers & Modifiers::MODIFIER_ABSTRACT);
    }
}
