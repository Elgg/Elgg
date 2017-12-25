<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Kernel;

/**
 * Exception and error handler
 *
 * @access private
 */
class ExceptionHandler {

	/**
	 * @var Application
	 */
	protected $application;

	/**
	 * @var Kernel
	 */
	protected $kernel;

	/**
	 * Kernel constructor.
	 *
	 * @param Application $application Application
	 * @param Kernel      $kernel      Kernel
	 */
	public function __construct(Application $application, Kernel $kernel) {
		$this->application = $application;
		$this->kernel = $kernel;
	}

	/**
	 * Intercepts, logs, and displays uncaught exceptions.
	 *
	 * To use a viewtype other than failsafe, create the views:
	 *  <viewtype>/messages/exceptions/admin_exception
	 *  <viewtype>/messages/exceptions/exception
	 * See the json viewtype for an example.
	 *
	 * @warning This function should never be called directly.
	 *
	 * @see     http://www.php.net/set-exception-handler
	 *
	 * @param \Exception|\Error $exception The exception/error being handled
	 *
	 * @return void
	 * @throws \InvalidParameterException
	 * @throws \SecurityException
	 * @access  private
	 */
	public function handleExceptions($exception) {
		$timestamp = time();
		error_log("Exception at time $timestamp: $exception");

		// Wipe any existing output buffer
		ob_end_clean();

		// make sure the error isn't cached
		header("Cache-Control: no-cache, must-revalidate", true);
		header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', true);

		if ($exception instanceof \InstallationException) {
			forward('/install.php');
		}

		if (Bootstrap::isCoreLoaded()) {
			http_response_code(500);
			echo "Exception loading Elgg core. Check log at time $timestamp";

			return;
		}

		try {
			// allow custom scripts to trigger on exception
			// value in settings.php should be a system path to a file to include
			$exception_include = $this->application->_services->config->exception_include;

			if ($exception_include && is_file($exception_include)) {
				ob_start();

				// don't isolate, these scripts may use the local $exception var.
				include $exception_include;

				$exception_output = ob_get_clean();

				// if content is returned from the custom handler we will output
				// that instead of our default failsafe view
				if (!empty($exception_output)) {
					echo $exception_output;
					exit;
				}
			}

			if (elgg_is_xhr()) {
				elgg_set_viewtype('json');
				$response = new \Symfony\Component\HttpFoundation\JsonResponse(null, 500);
			} else {
				elgg_set_viewtype('failsafe');
				$response = new \Symfony\Component\HttpFoundation\Response('', 500);
			}

			if (elgg_is_admin_logged_in()) {
				$body = elgg_view("messages/exceptions/admin_exception", [
					'object' => $exception,
					'ts' => $timestamp
				]);
			} else {
				$body = elgg_view("messages/exceptions/exception", [
					'object' => $exception,
					'ts' => $timestamp
				]);
			}

			$response->setContent(elgg_view_page(elgg_echo('exception:title'), $body));
			$response->send();
		} catch (\Exception $e) {
			$timestamp = time();
			$message = $e->getMessage();
			http_response_code(500);
			echo "Fatal error in exception handler. Check log for Exception at time $timestamp";
			error_log("Exception at time $timestamp : fatal error in exception handler : $message");
		}
	}

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
	 * @see     http://www.php.net/set-error-handler
	 *
	 * @param int    $errno    The level of the error raised
	 * @param string $errmsg   The error message
	 * @param string $filename The filename the error was raised in
	 * @param int    $linenum  The line number the error was raised at
	 * @param array  $vars     An array that points to the active symbol table where error occurred
	 *
	 * @return true
	 * @throws \Exception
	 * @access  private
	 */
	public function handleErrors($errno, $errmsg, $filename, $linenum, $vars) {
		$error = date("Y-m-d H:i:s (T)") . ": \"$errmsg\" in file $filename (line $linenum)";

		$log = function ($message, $level) {
			if (Bootstrap::isCoreLoaded()) {
				return elgg_log($message, $level);
			}

			return false;
		};

		switch ($errno) {
			case E_USER_ERROR:
				if (!$log("PHP: $error", 'ERROR')) {
					error_log("PHP ERROR: $error");
				}
				if (Bootstrap::isCoreLoaded()) {
					register_error("ERROR: $error");
				}

				// Since this is a fatal error, we want to stop any further execution but do so gracefully.
				throw new \Exception($error);

			case E_WARNING :
			case E_USER_WARNING :
			case E_RECOVERABLE_ERROR: // (e.g. type hint violation)

				// check if the error wasn't suppressed by the error control operator (@)
				if (error_reporting() && !$log("PHP: $error", 'WARNING')) {
					error_log("PHP WARNING: $error");
				}
				break;

			default:
				if (function_exists('_elgg_config')) {
					$debug = _elgg_config()->debug;
				} else {
					$debug = isset($GLOBALS['CONFIG']->debug) ? $GLOBALS['CONFIG']->debug : null;
				}
				if ($debug !== 'NOTICE') {
					return true;
				}

				if (!$log("PHP (errno $errno): $error", 'NOTICE')) {
					error_log("PHP NOTICE: $error");
				}
		}

		return true;
	}

}
