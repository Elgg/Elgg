<?php

/**
 * SuccessResult
 * Generic success result class, extend if you want to do something special.
 */
class SuccessResult extends \GenericResult {
	
	public const RESULT_SUCCESS = 0;

	/**
	 * A new success result
	 *
	 * @param mixed $result The result
	 */
	public function __construct(mixed $result) {
		$this->setResult($result);
		$this->setStatusCode(self::RESULT_SUCCESS);
		$this->setHttpStatus(ELGG_HTTP_OK);
	}

	/**
	 * Returns a new instance of this class
	 *
	 * @param mixed $result A result of some kind?
	 *
	 * @return static
	 */
	public static function getInstance(mixed $result): static {
		return new static($result);
	}
}
