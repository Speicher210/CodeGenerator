<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

use Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException;
use Wingu\OctopusCore\CodeGenerator\Expression;

/**
 * Dump a value as valid PHP code.
 */
class ValueGenerator extends PHPGenerator
{

    const TYPE_AUTO = 'auto';

    const TYPE_NULL = 'null';

    const TYPE_OBJECT = 'object';

    const TYPE_BOOLEAN = 'boolean';

    const TYPE_NUMBER = 'number';

    const TYPE_INTEGER = 'integer';

    const TYPE_FLOAT = 'float';

    const TYPE_DOUBLE = 'double';

    const TYPE_STRING = 'string';

    const TYPE_ARRAY = 'array';

    const TYPE_CONSTANT = 'constant';

    const TYPE_EXPRESSION = 'expression';

    const TYPE_OTHER = 'other';

    const OUTPUT_MULTI_LINE = 'multi';

    const OUTPUT_SINGLE_LINE = 'single';

    /**
     * The value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * The type of the value.
     *
     * @var string
     */
    protected $type = self::TYPE_AUTO;

    /**
     * The output mode.
     *
     * @var string
     */
    protected $outputMode = self::OUTPUT_MULTI_LINE;

    /**
     * Constructor.
     *
     * @param mixed $value The value.
     * @param string $type The value type.
     * @param string $outputMode The output mode.
     */
    public function __construct($value = null, $type = self::TYPE_AUTO, $outputMode = self::OUTPUT_MULTI_LINE)
    {
        $this->setValue($value);
        $this->setType($type);
        $this->setOutputMode($outputMode);
    }

    /**
     * Set the value.
     *
     * @param mixed $value The value to set.
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the type of the value.
     *
     * @param string $type The type of the value.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the type of the value.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the output mode.
     *
     * @param string $outputMode The output mode.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\ValueGenerator
     */
    public function setOutputMode($outputMode)
    {
        $this->outputMode = ($outputMode === self::OUTPUT_MULTI_LINE) ? self::OUTPUT_MULTI_LINE : self::OUTPUT_SINGLE_LINE;
        return $this;
    }

    /**
     * Get the output mode.
     *
     * @return string
     */
    public function getOutputMode()
    {
        return $this->outputMode;
    }

    /**
     * Check if the value type is valid for a constant.
     *
     * @return boolean
     */
    public function isValidConstantType()
    {
        if ($this->type === self::TYPE_AUTO) {
            $type = $this->determineType($this->value);
        } else {
            $type = $this->type;
        }

        // Valid types for constants.
        $scalarTypes = [
            self::TYPE_BOOLEAN,
            self::TYPE_NUMBER,
            self::TYPE_INTEGER,
            self::TYPE_FLOAT,
            self::TYPE_DOUBLE,
            self::TYPE_STRING,
            self::TYPE_CONSTANT,
            self::TYPE_NULL,
            self::TYPE_EXPRESSION
        ];

        return in_array($type, $scalarTypes);
    }

    /**
     * Determine the value type.
     *
     * @param mixed $value The value from witch to determine the type.
     * @return string
     */
    protected function determineType($value)
    {
        if ($value instanceof Expression || $value instanceof ValueGenerator) {
            return self::TYPE_EXPRESSION;
        }

        switch (gettype($value)) {
            case 'boolean':
                return self::TYPE_BOOLEAN;
            case 'integer':
                return self::TYPE_INTEGER;
            case 'string':
                return self::TYPE_STRING;
            case 'double':
            case 'float':
                return self::TYPE_NUMBER;
            case 'array':
                return self::TYPE_ARRAY;
            case 'NULL':
                return self::TYPE_NULL;
            case 'object':
                return self::TYPE_OBJECT;
            default:
                return self::TYPE_OTHER;
        }
    }

    /**
     * Escape string values.
     *
     * @param string $value The string to escape.
     * @param boolean $quote If the string should be quoted or not.
     * @return string
     */
    public function escapeStringValue($value, $quote = true)
    {
        $output = addcslashes((string)$value, "'");
        if ($quote === true) {
            $output = "'" . $output . "'";
        }

        return $output;
    }

    /**
     * Generate the code.
     *
     * @return string
     * @throws \Wingu\OctopusCore\CodeGenerator\Exceptions\RuntimeException If the type of the value is not valid.
     */
    public function generate()
    {
        $type = $this->type;
        $value = $this->value;

        if ($type === self::TYPE_AUTO) {
            $type = $this->determineType($value);
        }

        $code = '';
        switch ($type) {
            case self::TYPE_NULL:
                $code .= 'null';
                break;
            case self::TYPE_BOOLEAN:
                $code .= ((bool)$value === true) ? 'true' : 'false';
                break;
            case self::TYPE_NUMBER:
            case self::TYPE_INTEGER:
            case self::TYPE_FLOAT:
            case self::TYPE_DOUBLE:
            case self::TYPE_CONSTANT:
            case self::TYPE_EXPRESSION:
                $code .= (string)$value;
                break;
            case self::TYPE_STRING:
                $code .= $this->escapeStringValue($value);
                break;
            case self::TYPE_ARRAY:
                $code .= $this->generateArrayValue($value);
                break;
            default:
                throw new RuntimeException('A value of type "' . get_class($value) . '" can not be used.');
        }

        return $code;
    }

    /**
     * Prepare the array value.
     *
     * It will make sure that all the elements of the array can be generated.
     *
     * @param array $value The value to prepare.
     */
    protected function prepareArrayValue(array $value)
    {
        $rii = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($value), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($rii as $key => $value) {
            if (($value instanceof ValueGenerator) === false) {
                $value = new self($value);
                $rii->getSubIterator()->offsetSet($key, $value);
            }

            $value->setIndentationLevel($value->getIndentationLevel() + $rii->getDepth());
        }

        return $rii->getSubIterator()->getArrayCopy();
    }

    /**
     * Generate the code of an array.
     *
     * @param array $value The value to generate.
     * @return string
     */
    protected function generateArrayValue(array $value)
    {
        $value = $this->prepareArrayValue($value);
        $outputMode = count($value) > 1 ? $this->outputMode : self::OUTPUT_SINGLE_LINE;

        $code = 'array(';
        if ($outputMode === self::OUTPUT_MULTI_LINE) {
            $code .= $this->getLineFeed() . $this->getIndentation() . $this->getIndentationString(
                ) . $this->getIndentationString();
        }

        $outputParts = array();
        $noKeyIndex = 0;
        foreach ($value as $n => $v) {
            $v->setIndentationLevel($this->getIndentationLevel() + 1);
            $v->setOutputMode($this->outputMode);
            $partV = $v->generate();
            if ($n === $noKeyIndex) {
                $outputParts[] = $partV;
                $noKeyIndex++;
            } else {
                $outputParts[] = (is_int($n) === true ? $n : $this->escapeStringValue($n)) . ' => ' . $partV;
            }
        }

        if ($outputMode === self::OUTPUT_MULTI_LINE) {
            $padding = $this->getLineFeed() . $this->getIndentation() . $this->getIndentationString(
                ) . $this->getIndentationString();
        } else {
            $padding = ' ';
        }

        $code .= implode(',' . $padding, $outputParts);
        if ($outputMode === self::OUTPUT_MULTI_LINE) {
            $code .= $this->getLineFeed() . $this->getIndentation() . $this->getIndentationString();
        }
        $code .= ')';

        return $code;
    }
}