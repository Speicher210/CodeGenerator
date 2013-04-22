<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Integration\Fixtures {

    /**
     * Short trait description.
     *
     * Long description.
     * On multiple lines.
     *
     * @baseAnnotation testing
     */
    trait MyTrait {

        use tt1;

        use tt2 {
            tt2::myfunc as myfunc2;
            tt2::func as func2;
        }

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

    trait tt1 {}
    trait tt2 {

        protected function myfunc() {}
        protected function func() {}
    }
}