<?php

namespace Wingu\OctopusCore\CodeGenerator\Tests\Unit;

use Wingu\OctopusCore\CodeGenerator\Expression;

class ExpressionTest extends TestCase {

	public function getDataExpressions() {
		return array(
			[null], [0], [''], [-123.456], [array(1,'2')], [new \stdClass()]
		);
	}

	/**
	 * @dataProvider getDataExpressions
	 */
	public function testGetExpression($expression) {
		$e = new Expression($expression);
		$this->assertSame($expression, $e->getExpression());
	}
}