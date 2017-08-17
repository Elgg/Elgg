<?php
/**
 *
 */

namespace Elgg;

use ElggUser;

class LegacyIntegrationTestCase extends IntegrationTestCase {

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

	public function assertEqual($expected, $actual) {
		return $this->assertEquals($expected, $actual);
	}

	public function assertNotEqual($expected, $actual) {
		return $this->assertNotEquals($expected, $actual);
	}

	public function assertWithinMargin($expected, $actual, $margin) {
		return $this->assertEquals($expected, $actual, '', $margin);
	}

	public function assertIdentical($expected, $actual) {
		return $this->assertSame($expected, $actual);
	}

	public function assertNotIdentical($expected, $actual) {
		return $this->assertNotSame($expected, $actual);
	}

	public function assertIsA($actual, $classname) {
		switch ($classname) {
			case 'array' :
				return $this->assertEquals('array', gettype($actual));
			case 'int' :
				return $this->assertInternalType('integer', $actual);
			case 'string' :
				return $this->assertInternalType('string', $actual);
		}

		return $this->assertInstanceOf($classname, $actual);
	}

	public function assertPattern($pattern, $string) {
		return $this->assertRegExp($pattern, $string);
	}

	public function assertNoPattern($pattern, $string) {
		return $this->assertNotRegExp($pattern, $string);
	}

	public function expectError() {
		return $this->setExpectedException('PHPUnit_Framework_Error');
	}
}