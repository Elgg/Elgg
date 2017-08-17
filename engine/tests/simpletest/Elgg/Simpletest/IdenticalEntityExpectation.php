<?php

namespace Elgg\Simpletest;

/**
 * Test for identity.
 * @package    SimpleTest
 * @subpackage UnitTester
 */
class IdenticalEntityExpectation extends \EqualExpectation {

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

		return \SimpleTestCompatibility::isIdentical($value, $compare);
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