<?php

namespace Elgg\Validation;

/**
 * Represents a parameter that has been validated
 */
class ValidationResult {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var string
	 */
	protected $error;

	/**
	 * Constructor
	 *
	 * @param string $name  Parameter name
	 * @param mixed  $value Parameter value
	 */
	public function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * Set parameter value as passing validation
	 *
	 * @param string $message Optional message
	 *
	 * @return void
	 */
	public function pass($message = '') {
		$this->message = $message;
	}

	/**
	 * Set parameter value as failing validation
	 *
	 * @param string $error Optional error message
	 *
	 * @return void
	 */
	public function fail($error = '') {
		$this->error = $error;
	}

	/**
	 * Is parameter value valid?
	 *
	 * @return bool
	 */
	public function isValid() {
		return !isset($this->error);
	}

	/**
	 * Get parameter name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get parameter valud
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Get error message
	 *
	 * @return mixed
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * Get success message
	 *
	 * @return mixed
	 */
	public function getMessage() {
		return $this->message;
	}
}
