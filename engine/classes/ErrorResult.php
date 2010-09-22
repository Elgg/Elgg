<?php
/**
 * ErrorResult
 * The error result class.
 *
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Core
 */
class ErrorResult extends GenericResult {
	// Fail with no specific code
	public static $RESULT_FAIL = -1 ;

	public static $RESULT_FAIL_APIKEY_DISABLED = -30;
	public static $RESULT_FAIL_APIKEY_INACTIVE = -31;
	public static $RESULT_FAIL_APIKEY_INVALID = -32;

	// Invalid, expired or missing auth token
	public static $RESULT_FAIL_AUTHTOKEN = -20;

	public function ErrorResult($message, $code = "", Exception $exception = NULL) {
		if ($code == "") {
			$code = ErrorResult::$RESULT_FAIL;
		}

		if ($exception!=NULL) {
			$this->setResult($exception->__toString());
		}

		$this->setStatusCode($code, $message);
	}

	/**
	 * Get a new instance of the ErrorResult.
	 *
	 * @param string $message
	 * @param int $code
	 * @param Exception $exception Optional exception for generating a stack trace.
	 */
	public static function getInstance($message, $code = "", Exception $exception = NULL) {
		// Return a new error object.
		return new ErrorResult($message, $code, $exception);
	}
}