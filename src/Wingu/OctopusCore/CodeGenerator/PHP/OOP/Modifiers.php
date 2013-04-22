<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\OOP;

/**
 * Interface that holds only constants for modifiers.
 */
interface Modifiers {

    /**
     * No modifier value.
     *
     * @var integer
     */
    const MODIFIER_NONE = 0x000;

    /**
     * Modifier for abstract member.
     *
     * @var integer
     */
    const MODIFIER_ABSTRACT = 0x010;

    /**
     * Modifier for final member.
     *
     * @var integer
     */
    const MODIFIER_FINAL = 0x040;

    /**
     * Modifier for static member.
     *
     * @var integer
     */
    const MODIFIER_STATIC = 0x001;

    /**
     * Modifier for public member.
     *
     * @var integer
     */
    const MODIFIER_PUBLIC = 0x100;

    /**
     * Modifier for protected member.
     *
     * @var integer
     */
    const MODIFIER_PROTECTED = 0x200;

    /**
     * Modifier for private member.
     *
     * @var integer
     */
    const MODIFIER_PRIVATE = 0x400;

    /**
     * Visibility public.
     *
     * @var string
     */
    const VISIBILITY_PUBLIC = 'public';

    /**
     * Visibility protected.
     *
     * @var string
     */
    const VISIBILITY_PROTECTED = 'protected';

    /**
     * Visibility private.
     *
     * @var string
     */
    const VISIBILITY_PRIVATE = 'private';
}