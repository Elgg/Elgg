<?php

namespace Elgg\Validation;

/**
 * Represents a set of validated parameters
 */
class ValidationResults {

	/**
	 * @var ValidationResult[]
	 */
	protected $results = [];

	/**
	 * Add parameter and set it as valid
	 *
	 * @param string $name    Parameter name
	 * @param mixed  $value   Parameter value
	 * @param string $message Success message
	 *
	 * @return static
	 */
	public function pass($name, $value, $message = '') {
		$result = new ValidationResult($name, $value);
		$result->pass($message);

		$this->results[$name] = $result;

		return $this;
	}

	/**
	 * Add parameter and set it as invalid
	 *
	 * @param string $name  Parameter name
	 * @param mixed  $value Parameter value
	 * @param string $error Error message
	 *
	 * @return static
	 */
	public function fail($name, $value, $error = '') {
		$result = new ValidationResult($name, $value);
		$result->fail($error);

		$this->results[$name] = $result;

		return $this;
	}

	/**
	 * Get all results in this bag
	 * @return ValidationResult[]
	 */
	public function all() {
		return $this->results;
	}

	/**
	 * Check if the bag has parameters that failed validation
	 * @return ValidationResult[]|false
	 */
	public function getFailures() {
		$failures = array_filter($this->results, function(ValidationResult $e) {
			return !$e->isValid();
		});

		return empty($failures) ? false : $failures;
	}
}