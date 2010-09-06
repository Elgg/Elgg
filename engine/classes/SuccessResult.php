<?php
/**
 * SuccessResult
 * Generic success result class, extend if you want to do something special.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Core
 */
class SuccessResult extends GenericResult {
	public static $RESULT_SUCCESS = 0;  // Do not change this from 0

	public function SuccessResult($result) {
		$this->setResult($result);
		$this->setStatusCode(SuccessResult::$RESULT_SUCCESS);
	}

	public static function getInstance($result) {
		// Return a new error object.
		return new SuccessResult($result);
	}
}