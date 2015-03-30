<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use BadMethodCallException;

/**
 * A class containing all the PHP reserved keywords.
 */
class ReservedKeywords extends \ArrayObject
{

    /**
     * An array of all the keywords.
     *
     * @var array
     */
    protected static $keywords = array(
        '__halt_compiler',
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endswitch',
        'endif',
        'endwhile',
        'eval',
        'exit',
        'extends',
        'final',
        'finally',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'include',
        'public',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
        'yield',
        '__class__',
        '__dir__',
        '__file__',
        '__function__',
        '__line__',
        '__method__',
        '__namespace__',
        '__trait__'
    );

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(array_combine(self::$keywords, self::$keywords));
    }

    /**
     * Returns whether the requested index exists.
     *
     * @param mixed $index The index being checked.
     * @return boolean
     */
    public function offsetExists($index)
    {
        return parent::offsetExists(strtolower($index));
    }

    /**
     * Returns the value at the specified index.
     *
     * @param mixed $index The index with the value.
     * @return mixed
     */
    public function offsetGet($index)
    {
        return parent::offsetGet(strtolower($index));
    }

    /**
     * Sets the value at the specified index to new value.
     *
     * @param mixed $index The index being set.
     * @param mixed $newValue The new value for the index.
     * @throws \BadMethodCallException If this method is called.
     */
    public function offsetSet($index, $newValue)
    {
        throw new BadMethodCallException('You can not set new reserved words.');
    }

    /**
     * Unset the value at the specified index.
     *
     * @param mixed $index The index being unset.
     * @throws \BadMethodCallException If this method is called.
     */
    public function offsetUnset($index)
    {
        throw new BadMethodCallException('You can not remove reserved words.');
    }

    /**
     * Check if a word is a reserved PHP keyword.
     *
     * @param string $word The word to check.
     * @return boolean
     */
    public static function isReservedKeyword($word)
    {
        return in_array(strtolower($word), self::$keywords);
    }
}
