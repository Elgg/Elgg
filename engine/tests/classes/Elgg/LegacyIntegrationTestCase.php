<?php

namespace Elgg;

use Elgg\Cache\Pool\TestCase;
use ElggUser;

/**
 * Proxies simpletest assertions and old simpletest unit case methods
 * so that we can easily port existing simpletests to PHPUnit
 *
 * DO NOT EXTEND THIS CLASS IF YOU ARE WRITING NEW TESTS
 *
 * @deprecated
 * @access private
 */
abstract class LegacyIntegrationTestCase extends IntegrationTestCase {

	/**
	 * Replace the current user session
	 *
	 * @param ElggUser $user New user to login as (null to log out)
	 *
	 * @return ElggUser|null Removed session user (or null)
	 */
	public function replaceSession(ElggUser $user = null) {
		$session = elgg_get_session();
		$old = $session->getLoggedInUser();
		if ($user) {
			$session->setLoggedInUser($user);
		} else {
			$session->removeLoggedInUser();
		}

		return $old;
	}

	/*************************************************
	 * Proxy for simpletest assertions               *
	 *************************************************/

	/**
	 * Will trigger a pass if the two entity parameters have
	 * the same "value" and same type. Otherwise a fail.
	 *
	 * @param mixed  $first   Entity to compare.
	 * @param mixed  $second  Entity to compare.
	 * @param string $message Message to display.
	 *
	 * @return boolean
	 */
	public function assertIdenticalEntities(\ElggEntity $first, \ElggEntity $second, $message = '%s') {
		if (!($res = $this->assertIsA($first, '\ElggEntity'))) {
			return $res;
		}
		if (!($res = $this->assertIsA($second, '\ElggEntity'))) {
			return $res;
		}
		if (!($res = $this->assertEqual(get_class($first), get_class($second)))) {
			return $res;
		}

		return $this->assert(new IdenticalEntityExpectation($first), $second, $message);
	}

	public static function assertTrue($condition, $message = '') {
		// PHPUnit expects an actual boolean
		return TestCase::assertTrue((bool) $condition, $message);
	}

	public static function assertFalse($condition, $message = '') {
		// PHPUnit expects an actual boolean
		return TestCase::assertFalse((bool) $condition, $message);
	}

	public function assertEqual($expected, $actual, $message = '') {
		return $this->assertEquals($expected, $actual, $message);
	}

	public function assertNotEqual($expected, $actual, $message = '') {
		return $this->assertNotEquals($expected, $actual, $message);
	}

	public function assertWithinMargin($expected, $actual, $margin, $message = '') {
		return $this->assertEquals($expected, $actual, $message, $margin);
	}

	public function assertIdentical($expected, $actual, $message = '') {
		return $this->assertSame($expected, $actual, $message);
	}

	public function assertNotIdentical($expected, $actual, $message = '') {
		return $this->assertNotSame($expected, $actual, $message);
	}

	public function assertIsA($actual, $classname, $message = '') {
		switch ($classname) {
			case 'array' :
				return $this->assertEquals('array', gettype($actual), $message);
			case 'int' :
				return $this->assertInternalType('integer', $actual, $message);
			case 'string' :
				return $this->assertInternalType('string', $actual, $message);
		}

		return $this->assertInstanceOf($classname, $actual, $message);
	}

	public function assertPattern($pattern, $string, $message ='') {
		return $this->assertRegExp($pattern, $string, $message);
	}

	public function assertNoPattern($pattern, $string, $message = '') {
		return $this->assertNotRegExp($pattern, $string, $message);
	}

	public function skipUnless($condition, $message) {
		if (!$condition) {
			$this->markTestSkipped($message);
		}
	}

	public function skipIf($condition, $message) {
		if ($condition) {
			$this->markTestSkipped($message);
		}
	}
}