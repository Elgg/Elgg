<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Exceptions\ErrorException;
use Elgg\Traits\Loggable;
use Psr\Log\LogLevel;

/**
 * Handle system and PHP errors
 *
 * @internal
 */
class ErrorHandler {

	use Loggable;

	/**
	 * Intercepts catchable PHP errors.
	 *
	 * @warning This function should never be called directly.
	 *
	 * @internal
	 * For catchable fatal errors, throws an Exception with the error.
	 *
	 * For non-fatal errors, depending upon the debug settings, either
	 * log the error or ignore it.
	 *
	 * @see http://www.php.net/set-error-handler
	 *
	 * @param int    $errno    The level of the error raised
	 * @param string $errmsg   The error message
	 * @param string $filename The filename the error was raised in
	 * @param int    $linenum  The line number the error was raised at
	 *
	 * @return true
	 * @throws \Elgg\Exceptions\ErrorException
	 */
	public function __invoke($errno, $errmsg, $filename = '', $linenum = 0) {
		$error = date("Y-m-d H:i:s (T)") . ": \"$errmsg\" in file $filename (line $linenum)";

		// check if the error wasn't suppressed by the error control operator (@)
		// error_reporting === 0 for PHP < 8.0
		$reporting_disabled = (error_reporting() === 0) || !(error_reporting() & $errno);
		
		switch ($errno) {
			case E_USER_ERROR:
				$this->log(LogLevel::ERROR, "PHP ERROR: $error");

				if (Application::isCoreLoaded()) {
					Application::$_instance->internal_services->system_messages->addErrorMessage("ERROR: $error");
				}

				// Since this is a fatal error, we want to stop any further execution but do so gracefully.
				throw new ErrorException($error, 0, $errno, $filename, $linenum);

			case E_WARNING :
			case E_USER_WARNING :
			case E_RECOVERABLE_ERROR: // (e.g. type hint violation)
				if (!$reporting_disabled) {
					$this->log(LogLevel::WARNING, "PHP: $error");
				}
				break;

			default:
				if (!$reporting_disabled) {
					$this->log(LogLevel::NOTICE, "PHP NOTICE: $error");
				}
				
				break;
		}

		return true;
	}
}
