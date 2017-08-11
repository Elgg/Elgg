<?php
/**
 *
 */

namespace Elgg;


use ElggUser;

class LegacyIntegrationTestCase extends IntegrationTestCase {

	/** @var array */
	protected $types;

	/** @var array */
	protected $subtypes;

	public function setUp() {

		parent::setUp();

		$this->subtypes = [];

		// sites are a bit wonky.  Don't use them just now.
		$this->types = [
			'object',
			'user',
			'group'
		];

		for ($i = 0; $i < 5; $i++) {
			foreach ($this->types as $type) {
				$uid = sha1(microtime() . $i . $type);
				$subtype = "subtype_{$uid}";
				$this->subtypes[$type][] = $subtype;
			}
		}

	}

	public function tearDown() {
		parent::tearDown();
	}

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
	 * Helpers for getting random types and subtypes *
	 *************************************************/

	/**
	 * Get a random valid subtype
	 *
	 * @param int $num
	 *
	 * @return array
	 */
	protected function getRandomValidTypes($num = 1) {
		$types = $this->types;
		shuffle($types);

		return array_slice($types, 0, $num);
	}

	/**
	 * Get a random valid subtype for an entity type
	 *
	 * @param string $type Entity type
	 *
	 * @return string
	 */
	protected function getRandomValidSubtype($type = 'object') {
		$subtypes = $this->getRandomValidSubtypes([$type], 1);

		return array_shift($subtypes);
	}

	/**
	 * Get a random valid subtype (that we just created)
	 *
	 * @param array $type Type of objects to return valid subtypes for.
	 * @param int   $num  of subtypes.
	 *
	 * @return array
	 */
	protected function getRandomValidSubtypes(array $types, $num = 1) {
		$r = [];

		for ($i = 1; $i <= $num; $i++) {
			do {
				// make sure at least one subtype of each type is returned.
				if ($i - 1 < count($types)) {
					$type = $types[$i - 1];
				} else {
					$type = $types[array_rand($types)];
				}

				$k = array_rand($this->subtypes[$type]);
				$t = $this->subtypes[$type][$k];
			} while (in_array($t, $r));

			$r[] = $t;
		}

		shuffle($r);

		return $r;
	}

	/**
	 * Return an array of invalid strings for type or subtypes.
	 *
	 * @param int $num
	 *
	 * @return string[]
	 */
	protected function getRandomInvalids($num = 1) {
		$r = [];

		for ($i = 1; $i <= $num; $i++) {
			$r[] = 'random_invalid_' . rand();
		}

		return $r;
	}

	/**
	 * Get a mix of valid and invalid types
	 *
	 * @param int $num
	 *
	 * @return array
	 */
	protected function getRandomMixedTypes($num = 2) {
		$have_valid = $have_invalid = false;
		$r = [];

		// need at least one of each type.
		$valid_n = rand(1, $num - 1);
		$r = array_merge($r, $this->getRandomValidTypes($valid_n));
		$r = array_merge($r, $this->getRandomInvalids($num - $valid_n));

		shuffle($r);

		return $r;
	}

	/**
	 * Get random mix of valid and invalid subtypes for types given.
	 *
	 * @param array $types
	 * @param int   $num
	 *
	 * @return array
	 */
	protected function getRandomMixedSubtypes(array $types, $num = 2) {
		$types_c = count($types);
		$r = [];

		// this can be more efficient but I'm very sleepy...

		// want at least one of valid and invalid of each type sent.
		for ($i = 0; $i < $types_c && $num > 0; $i++) {
			// make sure we have a valid and invalid for each type
			if (true) {
				$type = $types[$i];
				$r = array_merge($r, $this->getRandomValidSubtypes([$type], 1));
				$r = array_merge($r, $this->getRandomInvalids(1));

				$num -= 2;
			}
		}

		if ($num > 0) {
			$valid_n = rand(1, $num);
			$r = array_merge($r, $this->getRandomValidSubtypes($types, $valid_n));
			$r = array_merge($r, $this->getRandomInvalids($num - $valid_n));
		}

		//shuffle($r);
		return $r;
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