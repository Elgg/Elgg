<?php
/**
 * SuccessResult
 * Generic success result class, extend if you want to do something special.
 */
class SuccessResult extends GenericResult {
	
	public const RESULT_SUCCESS = 0;

	/**
	 * A new success result
	 *
	 * @param mixed $result The result
	 */
	public function __construct($result) {
		$this->setResult($result);
		$this->setStatusCode(self::RESULT_SUCCESS);
	}

	/**
	 * Returns a new instance of this class
	 *
	 * @param mixed $result A result of some kind?
	 *
	 * @return SuccessResult
	 */
	public static function getInstance($result) {
		// Return a new error object.
		return new SuccessResult($result);
	}
}
