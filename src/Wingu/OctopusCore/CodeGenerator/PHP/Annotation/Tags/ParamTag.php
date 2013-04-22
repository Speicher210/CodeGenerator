<?php

namespace Wingu\OctopusCore\CodeGenerator\PHP\Annotation\Tags;

/**
 * Documentation annotation "param" tag generator.
 */
class ParamTag extends BaseTag {

    /**
     * The type of the parameter.
     *
     * @var string
     */
    protected $paramType;

    /**
     * The name of the parameter.
     *
     * @var string
     */
    protected $paramName;

    /**
     * The description of the parameter.
     *
     * @var string
     */
    protected $paramDescription;

    /**
     * Constructor.
     *
     * @param string $type The type of the parameter.
     * @param string $name The name of the parameter.
     * @param string $description The parameter description / detail.
     */
    public function __construct($type, $name, $description = null) {
        $this->setParamType($type);
        $this->paramName = $name;
        $this->paramDescription = $description;
        parent::__construct('param');
    }

    /**
     * Set the parameter type.
     *
     * @param string $type the parameter type.
     */
    public function setParamType($type) {
        $this->paramType = $type;
    }

    /**
     * Generate the description part of the annotation tag.
     *
     * @return string
     */
    protected function generateDescriptionPart() {
        return trim($this->paramType . ' $' . $this->paramName . ' ' . $this->paramDescription);
    }
}