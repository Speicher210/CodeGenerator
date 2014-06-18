<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

/**
 * Documentation annotation "method" tag generator.
 */
class MethodTag extends BaseTag
{

    /**
     * The name of the method.
     *
     * @var string
     */
    protected $methodName;

    /**
     * The type of the return of the method.
     *
     * @var string
     */
    protected $methodReturn = 'void';

    /**
     * The method description.
     *
     * @var string
     */
    protected $methodDescription;

    /**
     * Constructor.
     *
     * @param string $methodName The name of the method.
     * @param string $methodReturn The type of the return of the method.
     * @param string $methodDescription The method description.
     */
    public function __construct($methodName, $methodReturn = null, $methodDescription = null)
    {
        parent::__construct('method');

        $this->setMethodName($methodName);
        $this->setMethodReturn($methodReturn);
        $this->setMethodDescription($methodDescription);
    }

    /**
     * Set the name of the method.
     *
     * @param string $methodName The name of the method.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\MethodTag
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
        return $this;
    }

    /**
     * Get the name of the method.
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * Set the method return type.
     *
     * @param string $return The return type.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\MethodTag
     */
    public function setMethodReturn($return)
    {
        if (trim((string)$return) === '') {
            $return = 'void';
        }

        $this->methodReturn = $return;
        return $this;
    }

    /**
     * Get the method return type.
     *
     * @return string
     */
    public function getMethodReturn()
    {
        return $this->methodReturn;
    }

    /**
     * Set the method description.
     *
     * @param string $description The method description.
     * @return \Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags\MethodTag
     */
    public function setMethodDescription($description)
    {
        $this->methodDescription = $description;
        return $this;
    }

    /**
     * Get the method description.
     *
     * @return string
     */
    public function getMethodDescription()
    {
        return $this->methodDescription;
    }

    /**
     * Generate the description part of the annotation tag.
     *
     * @return string
     */
    protected function generateDescriptionPart()
    {
        return $this->getMethodReturn() . ' ' . $this->getMethodName() . ' ' . $this->getMethodDescription();
    }
}
