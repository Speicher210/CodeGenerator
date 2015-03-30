<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

/**
 * Trait to deal with modifiers for object members (properties, methods, etc).
 */
trait ModifiersStaticTrait
{

    /**
     * Set the entity static.
     *
     * @param boolean $static Flag if the entity is static or not.
     */
    public function setStatic($static)
    {
        if ($static === true) {
            $this->addModifier(Modifiers::MODIFIER_STATIC);
        } else {
            $this->removeModifier(Modifiers::MODIFIER_STATIC);
        }
    }

    /**
     * Check if the entity is static or not.
     *
     * @return boolean
     */
    public function isStatic()
    {
        return (bool)($this->modifiers & Modifiers::MODIFIER_STATIC);
    }
}