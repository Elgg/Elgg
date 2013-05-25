<?php

/**
 * Elgg Core Unit Tester
 *
 * This class is to be extended by all Elgg unit tests. As such, any method here
 * will be available to the tests.
 */
abstract class ElggCoreUnitTest extends UnitTestCase {

	/**
	 * Class constructor.
	 *
	 * A simple wrapper to call the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
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
	 * @return boolean
	 */
	public function assertIdenticalEntities(ElggEntity $first, ElggEntity $second, $message = '%s') {
		if (!($res = $this->assertIsA($first, 'ElggEntity'))) {
			return $res;
		}
		if (!($res = $this->assertIsA($second, 'ElggEntity'))) {
			return $res;
		}
		if (!($res = $this->assertEqual(get_class($first), get_class($second)))) {
			return $res;
		}
		return $this->assert(new IdenticalEntityExpectation($first), $second, $message);
	}

}

/**
 * Test for identity.
 * @package SimpleTest
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
	 * @param ElggEntity $entity An entity to convert
	 * @return array
	 */
	protected function entityToFilteredArray($entity) {
		$skippedKeys = array('last_action');
		$array = (array)$entity;
		unset($array["\0*\0tables_loaded"]);
		foreach ($skippedKeys as $key) {
			// See: http://www.php.net/manual/en/language.types.array.php#language.types.array.casting
			$array["\0*\0attributes"][$key] = null;
		}
		ksort($array["\0*\0attributes"]);

		return $array;
	}

	/**
	 * Returns a human readable test message.
	 *
	 * @param mixed $compare Comparison value.
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
