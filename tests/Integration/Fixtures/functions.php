<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures;

/**
 * Short function description.
 *
 * Long description.
 * On multiple lines.
 *
 * @param array $array The array to sum up.
 * @param float $times The multiplier.
 * @param \DateTime $datetime Dummy variable.
 * @param integer $constant Test some constant.
 * @return float
 */
function myTestFunction(array $array, $times = 1, \DateTime $datetime = null, $constant = SORT_ASC) {
    return array_sum($array) * $times;
}

function testFunctionNoDocComment() {
}