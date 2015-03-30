<?php

namespace Wingu\OctopusCore\CodeGenerator;

/**
 * Abstract implementation of a code generator.
 */
abstract class AbstractGenerator implements GeneratorInterface
{

    /**
     * Current indentation string to use.
     *
     * @var string
     */
    protected $indentationString = self::INDENTATION_STRING_SPACE;

    /**
     * Current indentation level.
     *
     * @var integer
     */
    protected $indentationLevel = 0;

    /**
     * Current newline representation.
     *
     * @var string
     */
    protected $lineFeed = self::LINEFEED_UNIX;

    /**
     * Set current indentation string.
     *
     * @param string $value The indentation string.
     * @return \Wingu\OctopusCore\CodeGenerator\GeneratorInterface
     */
    public function setIndentationString($value)
    {
        $this->indentationString = $value;

        return $this;
    }

    /**
     * Get current indentation string.
     *
     * @return string
     */
    public function getIndentationString()
    {
        return $this->indentationString;
    }

    /**
     * Set current indentation level.
     *
     * @param integer $value The indentation level.
     * @return \Wingu\OctopusCore\CodeGenerator\GeneratorInterface
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the value is invalid.
     */
    public function setIndentationLevel($value)
    {
        if ($value < 0 || (!is_string($value) && is_infinite($value))) {
            throw new Exceptions\InvalidArgumentException('The indentation level can not be negative or infinite.');
        }

        $this->indentationLevel = intval($value);

        return $this;
    }

    /**
     * Add an indentation level on the current level.
     *
     * @param integer $value The indentation level.
     * @return \Wingu\OctopusCore\CodeGenerator\GeneratorInterface
     */
    public function addIndentationLevel($value)
    {
        $this->indentationLevel = max(0, $this->indentationLevel + (int)$value);

        return $this;
    }

    /**
     * Get current indentation level.
     *
     * @return integer
     */
    public function getIndentationLevel()
    {
        return $this->indentationLevel;
    }

    /**
     * Get the indentation calculated from the indentation level and the indentation string.
     *
     * @return string
     */
    protected function getIndentation()
    {
        return str_repeat($this->indentationString, $this->indentationLevel);
    }

    /**
     * Set current newline feed.
     *
     * @param string $lineFeed The line feed.
     * @return \Wingu\OctopusCore\CodeGenerator\GeneratorInterface
     */
    public function setLineFeed($lineFeed)
    {
        $this->lineFeed = $lineFeed;

        return $this;
    }

    /**
     * Get current line feed.
     *
     * @return string
     */
    public function getLineFeed()
    {
        return $this->lineFeed;
    }

    /**
     * Converting the object to string will result in generating the code.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->generate();
    }
}
