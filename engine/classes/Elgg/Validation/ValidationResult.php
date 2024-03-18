<?php

namespace Elgg\Validation;

/**
 * Represents a parameter that has been validated
 */
class ValidationResult {

	protected string $message = '';

	protected string $error = '';

	/**
	 * Constructor
	 *
	 * @param string $name  Parameter name
	 * @param mixed  $value Parameter value
	 */
	public function __construct(protected string $name, protected $value) {
	}

	/**
	 * Set parameter value as passing validation
	 *
	 * @param string $message Optional message
	 *
	 * @return void
	 */
	public function pass(string $message = ''): void {
		$this->message = $message;
	}

	/**
	 * Set parameter value as failing validation
	 *
	 * @param string $error Optional error message
	 *
	 * @return void
	 */
	public function fail(string $error = ''): void {
		$this->error = $error;
	}

	/**
	 * Is parameter value valid?
	 *
	 * @return bool
	 */
	public function isValid(): bool {
		return $this->error === '';
	}

	/**
	 * Get parameter name
	 *
	 * @return string
	 */
	public function getName(): string {
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
	 * @return string
	 */
	public function getError(): string {
		return $this->error;
	}

	/**
	 * Get success message
	 *
	 * @return string
	 */
	public function getMessage(): string {
		return $this->message;
	}
}
