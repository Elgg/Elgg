<?php

namespace Elgg\WebServices\Middleware;

use Elgg\Request;
use Elgg\WebServices\Di\RestApiErrorHandler;

/**
 * Set custom error/exception handlers during rest api calls
 *
 * @since 4.0
 */
class RestApiErrorHandlingMiddleware {
	
	/**
	 * Invoke middleware
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return void
	 */
	public function __invoke(Request $request) {
		
		set_exception_handler([$this, 'exceptionHandler']);
		
		if (!elgg_get_config('debug')) {
			return;
		}
		
		// add logger to default elgg logger
		$handler = RestApiErrorHandler::instance();
		
		elgg()->logger->pushHandler($handler);
	}
	
	/**
	 * API PHP Exception handler.
	 *
	 * This is a generic exception handler for PHP exceptions. This will catch any
	 * uncaught exception, end API execution and return the result to the requestor
	 * as an ErrorResult in the requested format.
	 *
	 * @param \Throwable $throwable throwable
	 *
	 * @return void
	 */
	public function exceptionHandler(\Throwable $throwable) {
		error_log('*** FATAL EXCEPTION (API) *** : ' . $throwable);
		
		$code = $throwable->getCode() === 0 ? \ErrorResult::$RESULT_FAIL : $throwable->getCode();
		$result = new \ErrorResult($throwable->getMessage(), $code);
		
		echo elgg_view_page($throwable->getMessage(), elgg_view('api/output', [
			'result' => $result,
		]));
	}
}
