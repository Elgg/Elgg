<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Exceptions\Configuration\InstallationException;
use Elgg\Http\Request;
use Elgg\Traits\Loggable;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handler for uncaught exceptions
 *
 * @internal
 */
class ExceptionHandler {

	use Loggable;

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
	 */
	public function __invoke($exception) {
		$exception->timestamp = time();
		$exception->uncaught = true;

		$this->log(LogLevel::CRITICAL, $exception);

		// Wipe any existing output buffer
		ob_end_clean();

		$request = Request::createFromGlobals();
		$headers = [
			'Cache-Control' => 'no-store, must-revalidate',
			'Expires' => 'Fri, 05 Feb 1982 00:00:00 -0500',
		];

		if ($exception instanceof InstallationException) {
			$response = RedirectResponse::create('/install.php', 307, $headers);
			$response->prepare($request);

			$response->send();

			return;
		}

		$app = Application::$_instance;

		if (!$app || !$app->_services) {
			$msg = "Exception loading Elgg core. Check log at time {$exception->timestamp}";
			$response = Response::create($msg, 500, $headers);
			$response->prepare($request);

			$response->send();

			return;
		}

		$services = $app->_services;
		if ($services->responseFactory->getSentResponse() !== false) {
			return;
		}
		
		try {
			// allow custom scripts to trigger on exception
			// value in settings.php should be a system path to a file to include
			$exception_include = $services->config->exception_include;

			if ($exception_include && is_file($exception_include)) {
				ob_start();

				// don't isolate, these scripts may use the local $exception var.
				include $exception_include;

				$exception_output = ob_get_clean();

				// if content is returned from the custom handler we will output
				// that instead of our default failsafe view
				if (!empty($exception_output)) {
					$response = Response::create($exception_output, 500, $headers);
					$response->prepare($request);

					$response->send();

					return;
				}
			}

			if (Application::isCli()) {
				// An error has already been logged
				return;
			}

			if ($services->request->isXmlHttpRequest()) {
				$services->views->setViewtype('json');
				$response = new JsonResponse(null, 500, $headers);
			} else {
				$services->views->setViewtype('failsafe');
				$response = new Response('', 500, $headers);
			}

			$body = elgg_view("messages/exceptions/exception", [
				'object' => $exception,
				'ts' => $exception->timestamp,
			]);

			$response->prepare($services->request);
			$response->setContent(elgg_view_page(elgg_echo('exception:title'), $body));
			$response->send();
		} catch (\Exception $e) {
			$timestamp = time();

			$e->timestamp = $timestamp;

			$this->log(LogLevel::CRITICAL, $e);

			$msg = "Fatal error in exception handler. Check log for Exception at time $timestamp";

			$response = new Response($msg, 500, $headers);
			$response->prepare($request);
			$response->send();
		}
	}
}
