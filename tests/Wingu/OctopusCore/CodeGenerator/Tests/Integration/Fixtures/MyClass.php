<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures {

    class c1 {}

    interface ci1 {}
    interface ci2 {}

    trait ct1 {}
    trait ct2 {
        use ct3;
    	public function myfunc() {}
    	public function func() {}
    }
    trait ct3 {
    	public function funcFromCT3() {}
    }

    /**
     * Short class description.
     *
     * Long description.
     * On multiple lines.
     *
     * @baseAnnotation testing
     */
    final class MyClass extends c1 implements ci1, ci2, \Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Dummy\ci3, \Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Dummy\ci4 {

        use ct1;

        use ct2 {
        	ct2::myfunc as protected myfunc2;
        	ct2::func as func2;
        }

        const MY_CONST1 = null;

        /**
         * My second constant.
         *
         * @var string
         */
        const MY_CONST2 = 'it\'s a string';

        public $publicProperty;

        protected $protectedProperty = 1;

        private $privateProperty = 'private';

        /**
         * Static property.
         *
         * @var array
         */
        protected static $array = array(1, null, 'string');

        /**
         * Constructor.
         *
         * @param mixed $param1 Parameter 1.
         * @param string $param2 Parameter 2.
        */
        public function __construct($param1, $param2 = null) {
        }

        /**
         * Protected function.
         *
         * @param array $array The array to count.
         * @return integer
         */
        protected function protectedFunc(array $array = array()) {
            return count($array);
        }

        /**
         * Private static function.
         *
         * This is my long function.
         *
         * @param \DateTime $datetime The date time.
         * @return boolean
         * @throws \InvalidArgumentException If the date is in the past.
         */
        final private static function privateStaticFunction(\DateTime $datetime) {
            if ($datetime < new \DateTime('now')) {
                throw new \InvalidArgumentException('Date is in the past.');
            }

            return true;
        }

    }
}

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures\Dummy {
    interface ci3 {}
    interface ci4 {}
}