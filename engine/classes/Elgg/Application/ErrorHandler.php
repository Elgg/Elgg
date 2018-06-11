<?php

namespace Elgg\Application;
use Elgg\Application;
use Elgg\Loggable;
use Exception;
use Psr\Log\LogLevel;

/**
 * Handle system and PHP errors
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
	 * @param array  $vars     An array that points to the active symbol table where error occurred
	 *
	 * @return true
	 * @throws Exception
	 * @access private
	 */
	public function __invoke($errno, $errmsg, $filename, $linenum, $vars) {
		$error = date("Y-m-d H:i:s (T)") . ": \"$errmsg\" in file $filename (line $linenum)";

		switch ($errno) {
			case E_USER_ERROR:
				$this->log(LogLevel::ERROR, "PHP ERROR: $error");

				if (Application::isCoreLoaded()) {
					Application::$_instance->_services->systemMessages->addErrorMessage("ERROR: $error");
				}

				// Since this is a fatal error, we want to stop any further execution but do so gracefully.
				throw new Exception($error);

			case E_WARNING :
			case E_USER_WARNING :
			case E_RECOVERABLE_ERROR: // (e.g. type hint violation)

				// check if the error wasn't suppressed by the error control operator (@)
				if (error_reporting()) {
					$this->log(LogLevel::WARNING, "PHP: $error");
				}
				break;

			default:
				$this->log(LogLevel::NOTICE, "PHP NOTICE: $error");
				break;
		}

		return true;
	}
}