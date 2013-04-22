<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException;

/**
 * Trait to deal with visibility modifiers.
 */
trait ModifiersVisibilityTrait {

    /**
     * Set the visibility of the entity.
     *
     * @param string $visibility The visibility to set.
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the visibility is invalid.
     */
    public function setVisibility($visibility) {
        switch ($visibility) {
            case Modifiers::VISIBILITY_PUBLIC:
                $this->removeModifier(Modifiers::MODIFIER_PRIVATE | Modifiers::MODIFIER_PROTECTED);
                $this->addModifier(Modifiers::MODIFIER_PUBLIC);
                break;
            case Modifiers::VISIBILITY_PROTECTED:
                $this->removeModifier(Modifiers::MODIFIER_PRIVATE | Modifiers::MODIFIER_PUBLIC);
                $this->addModifier(Modifiers::MODIFIER_PROTECTED);
                break;
            case Modifiers::VISIBILITY_PRIVATE:
                $this->removeModifier(Modifiers::MODIFIER_PUBLIC | Modifiers::MODIFIER_PROTECTED);
                $this->addModifier(Modifiers::MODIFIER_PRIVATE);
                break;
            default:
                throw new InvalidArgumentException('Unknown visibility to set.');
        }
    }

    /**
     * Get the visibility of the entity.
     *
     * @return string
     */
    public function getVisibility() {
        if (($this->modifiers & Modifiers::MODIFIER_PRIVATE) !== 0) {
            return Modifiers::VISIBILITY_PRIVATE;
        } elseif (($this->modifiers & Modifiers::MODIFIER_PROTECTED) !== 0) {
            return Modifiers::VISIBILITY_PROTECTED;
        } else {
            return Modifiers::VISIBILITY_PUBLIC;
        }
    }
}