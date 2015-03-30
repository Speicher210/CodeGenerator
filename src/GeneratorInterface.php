<?php

namespace Wingu\OctopusCore\CodeGenerator;

/**
 * Interface for all code generators.
 */
interface GeneratorInterface
{

    /**
     * The indentation string as a [tab].
     *
     * @var string
     */
    const INDENTATION_STRING_TAB = "\t";

    /**
     * The indentation string as a space (4 spaces).
     *
     * @var string
     */
    const INDENTATION_STRING_SPACE = '    ';

    /**
     * Text file line delimiter Unix style.
     *
     * @var string
     */
    const LINEFEED_UNIX = "\n";

    /**
     * Text file line delimiter Windows style.
     *
     * @var string
     */
    const LINEFEED_WIN = "\r\n";

    /**
     * Text file line delimiter using the style determined at the runtime (based on the OS).
     *
     * @var string
     */
    const LINEFEED_RUNTIME = PHP_EOL;

    /**
     * Set current indentation string.
     *
     * @param string $value The indentation string.
     * @return \Wingu\OctopusCore\CodeGenerator\GeneratorInterface
     */
    public function setIndentationString($value);

    /**
     * Get current indentation string.
     *
     * @return string
     */
    public function getIndentationString();

    /**
     * Set current indentation level.
     *
     * @param integer $value The indentation level.
     * @return \Wingu\OctopusCore\CodeGenerator\GeneratorInterface
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the value is invalid.
     */
    public function setIndentationLevel($value);

    /**
     * Add an indentation level on the current level.
     *
     * @param integer $value The indentation level.
     * @return \Wingu\OctopusCore\CodeGenerator\GeneratorInterface
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\InvalidArgumentException If the value is invalid.
     */
    public function addIndentationLevel($value);

    /**
     * Get current indentation level.
     *
     * @return integer
     */
    public function getIndentationLevel();

    /**
     * Set current newline feed.
     *
     * @param string $lineFeed The line feed.
     * @return \Wingu\OctopusCore\CodeGenerator\GeneratorInterface
     */
    public function setLineFeed($lineFeed);

    /**
     * Get current line feed.
     *
     * @return string
     */
    public function getLineFeed();

    /**
     * Generate the code.
     *
     * @return string
     */
    public function generate();
}
