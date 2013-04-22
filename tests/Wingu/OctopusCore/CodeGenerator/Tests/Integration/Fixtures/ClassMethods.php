<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures {

    abstract class ClassMethods {

        /**
         * Short method description.
         *
         * Long description.
         * On multiple lines.
         *
         * @param array $array The array to sum up.
         * @param float $times The multiplier.
         * @param \DateTime $datetime Dummy variable.
         * @param integer $const Sorting direction.
         * @return float
         */
        final private static function myTestMethod(array $array, $times = 1, \DateTime $datetime = null, $const = SORT_ASC,
                $c2 = OtherClass::SOME_CONSTANT,
                $c3 = \Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Sub\FixtureClassConstant::SOME_CONSTANT,
                $c4 = \Wingu\OctopusCore\CodeGenerator\Tests\FixtureClassConstant_123::SOME_CONSTANT) {
            return array_sum($array) * $times;
        }

        abstract protected function myAbstractFunction();

        abstract public function myAbstractPublicFunction();
    }

    class OtherClass {
        const SOME_CONSTANT = 1;
    }
}

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Sub {
    class FixtureClassConstant {
    	const SOME_CONSTANT = 1;
    }
}

namespace Wingu\OctopusCore\CodeGenerator\Tests {
	class FixtureClassConstant_123 {
		const SOME_CONSTANT = 1;
	}
}