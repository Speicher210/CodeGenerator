<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

/**
 * Trait to deal with modifiers.
 */
trait ModifiersBaseTrait {

    /**
     * The modifiers applied to the member.
     *
     * @var integer
     */
    protected $modifiers = Modifiers::MODIFIER_NONE;

    /**
     * Set modifiers for the member.
     *
     * @param integer|array $modifiers The modifiers to set.
     */
    protected function setModifiers($modifiers) {
        if (is_array($modifiers) === true) {
            $modifiersArray = $modifiers;
            $modifiers = Modifiers::MODIFIER_NONE;
            foreach ($modifiersArray as $modifier) {
                $modifiers |= $modifier;
            }
        }

        $this->modifiers = $modifiers;
    }

    /**
     * Add a modifier for the member.
     *
     * @param integer $modifier The modifier to add.
     */
    protected function addModifier($modifier) {
        $this->setModifiers($this->modifiers | $modifier);
    }

    /**
     * Remove a modifier for the member.
     *
     * @param integer $modifier The modifier to remove.
     */
    protected function removeModifier($modifier) {
        $this->setModifiers($this->modifiers & ~$modifier);
    }
}