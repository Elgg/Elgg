<?php

/**
 * Elgg Core Unit Tester
 *
 * This class is to be extended by all Elgg unit tests. As such, any method here
 * will be available to the tests.
 */
abstract class ElggCoreUnitTest extends UnitTestCase {

	/** @var array */
	protected $types;

	/** @var array */
	protected $subtypes;

	/**
	 * Class constructor.
	 *
	 * A simple wrapper to call the parent constructor.
	 */
	public function __construct() {
		parent::__construct();

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

	/**
	 * Class destructor.
	 *
	 * The parent does not provide a destructor, so including an explicit one here.
	 */
	public function __destruct() {

	}

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

	/**
	 * Generate random username
	 * @return string
	 */
	protected function generateRandomUsername() {
		return 'simpletest_user_' . sha1(microtime() . rand());
	}
}

/**
 * Test for identity.
 * @package    SimpleTest
 * @subpackage UnitTester
 */
class IdenticalEntityExpectation extends EqualExpectation {

	/**
	 * Sets the value to compare against.
	 *
	 * @param mixed  $value   Test value to match.
	 * @param string $message Customised message on failure.
	 */
	public function __construct($value, $message = '%s') {
		parent::__construct($value, $message);
	}

	/**
	 * Tests the expectation. True if it exactly matches the held value.
	 *
	 * @param mixed $compare Comparison value.
	 *
	 * @return boolean
	 */
	public function test($compare) {
		$value = $this->entityToFilteredArray($this->getValue());
		$compare = $this->entityToFilteredArray($compare);

		return SimpleTestCompatibility::isIdentical($value, $compare);
	}

	/**
	 * Converts entity to array and filters not important attributes
	 *
	 * @param \ElggEntity $entity An entity to convert
	 *
	 * @return array
	 */
	protected function entityToFilteredArray($entity) {
		$skippedKeys = ['last_action'];
		$array = (array) $entity;
		unset($array["\0*\0volatile"]);
		unset($array["\0*\0orig_attributes"]);
		foreach ($skippedKeys as $key) {
			// See: http://www.php.net/manual/en/language.types.array.php#language.types.array.casting
			unset($array["\0*\0attributes"][$key]);
		}
		ksort($array["\0*\0attributes"]);

		return $array;
	}

	/**
	 * Returns a human readable test message.
	 *
	 * @param mixed $compare Comparison value.
	 *
	 * @return string
	 */
	public function testMessage($compare) {
		$dumper = $this->getDumper();

		$value2 = $this->entityToFilteredArray($this->getValue());
		$compare2 = $this->entityToFilteredArray($compare);

		if ($this->test($compare)) {
			return "Identical entity expectation [" . $dumper->describeValue($this->getValue()) . "]";
		} else {
			return "Identical entity expectation [" . $dumper->describeValue($this->getValue()) .
				"] fails with [" .
				$dumper->describeValue($compare) . "] " .
				$dumper->describeDifference($value2, $compare2, TYPE_MATTERS);
		}
	}

}
