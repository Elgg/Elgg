<?php

namespace Elgg;

/**
 * Test for identity.
 * @package    SimpleTest
 * @subpackage UnitTester
 */
class IdenticalEntityExpectation extends \PHPUnit_Framework_Constraint_IsEqual {

	/**
	 * {@inheritdoc}
	 */
	public function evaluate($other, $description = '', $returnResult = false) {

		$this->value = $this->entityToFilteredArray($this->value);
		$other = $this->entityToFilteredArray($other);

		if (!$description) {
			$description = "Identical entity expectation failure";
		}

		return parent::evaluate($other, $description, $returnResult);
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


}