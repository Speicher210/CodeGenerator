<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP;

/**
 * A class to generate the "use" of objects or files.
 */
trait GlobalUseTrait
{

    /**
     * The uses in this file.
     *
     * @var array
     */
    protected $uses = array();

    /**
     * Set the uses.
     *
     * The uses items can be a string or an array.
     * If it is a string, then that is used as the use statements.
     * If an array, index 0 is the use and index 1 is the alias.
     *
     * @param mixed $uses The uses to set.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function setUses($uses)
    {
        $this->uses = array();

        return $this->addUses($uses);
    }

    /**
     * Add the uses.
     *
     * The uses items can be a string or an array.
     * If it is a string, then that is used as the use statements.
     * If an array, index 0 is the use and index 1 is the alias.
     *
     * @param mixed $uses The uses to set.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function addUses($uses)
    {
        foreach ($uses as $use) {
            if (is_string($use) === true) {
                $this->addUse($use);
            } elseif (is_array($use) === true) {
                $this->addUse($use[0], $use[1]);
            }
        }

        return $this;
    }

    /**
     * Add a use statement.
     *
     * @param string $use
     * @param string $alias
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\FileGenerator
     */
    public function addUse($use, $alias = null)
    {
        $this->uses[$use] = $alias;

        return $this;
    }

    /**
     * Get the uses.
     *
     * @return array
     */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * Render the uses.
     *
     * @return array
     */
    protected function renderUsesLines()
    {
        $code = array();
        $indentation = $this->getIndentation();
        foreach ($this->uses as $use => $alias) {
            if ($alias === null) {
                $code[] = $indentation . 'use ' . $use . ';';
            } else {
                $code[] = $indentation . 'use ' . $use . ' as ' . $alias . ';';
            }
        }

        if (count($this->uses) > 0) {
            $code[] = null;
        }

        return $code;
    }
}
