<?php
/**
 * ErrorResult
 * The error result class.
 */
class ErrorResult extends GenericResult {
	// Fail with no specific code
	public static $RESULT_FAIL = -1 ;

	public static $RESULT_FAIL_APIKEY_DISABLED = -30;
	public static $RESULT_FAIL_APIKEY_INACTIVE = -31;
	public static $RESULT_FAIL_APIKEY_INVALID = -32;

	// Invalid, expired or missing auth token
	public static $RESULT_FAIL_AUTHTOKEN = -20;

	/**
	 * A new error result
	 *
	 * @param string    $message   Message
	 * @param int       $code      Error Code
	 * @param Throwable $exception Exception object
	 *
	 * @return void
	 */
	public function __construct($message, $code = null, Throwable $exception = null) {
		if (!isset($code)) {
			$code = ErrorResult::$RESULT_FAIL;
		}

		if ($exception instanceof Throwable) {
			$this->setResult($exception->__toString());
		}

		$this->setStatusCode((int) $code, $message);
	}

	/**
	 * Get a new instance of the ErrorResult.
	 *
	 * @param string    $message   Message
	 * @param int       $code      Code
	 * @param Throwable $exception Optional exception for generating a stack trace.
	 *
	 * @return ErrorResult
	 */
	public static function getInstance($message, $code = null, Throwable $exception = null) {
		// Return a new error object.
		return new ErrorResult($message, $code, $exception);
	}
}
