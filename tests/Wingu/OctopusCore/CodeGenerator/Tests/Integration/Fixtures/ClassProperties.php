<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures;

class ClassProperties {

    /**
     * Short property description.
     *
     * Long description.
     * On multiple lines.
     *
     * @var array
     */
    protected static $array = array(1, null, 'string');

    public $property;

    private $protectedProperty = 1;
}