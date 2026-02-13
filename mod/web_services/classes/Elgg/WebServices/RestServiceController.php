<?php

namespace Elgg\WebServices;

use Elgg\Exceptions\AuthenticationException;
use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Elgg\WebServices\Di\ApiRegistrationService;
use Elgg\WebServices\Di\RestApiErrorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle /services/api/rest/... calls
 *
 * @since 4.0
 */
class RestServiceController {
	
	/**
	 * Handle an HTTP request
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request): ResponseBuilder {
		$this->prepareForRequest($request);
		
		$this->initApi();
		
		// Get parameter variables
		$method = (string) $request->getParam('method');
		
		// this will throw an exception if authentication fails
		try {
			$api = $this->authenticateMethod($method, $request->getMethod());
			
			// execute the api method
			$result = $api->execute($request);
		} catch (\APIException $e) {
			$prev = $e->getPrevious();
			if ($prev instanceof AuthenticationException) {
				$result = \ErrorResult::getInstance($prev->getMessage());
				$result->setHttpStatus(ELGG_HTTP_FORBIDDEN);
			} else {
				$result = \ErrorResult::getInstance($e->getMessage());
				$result->setHttpStatus(ELGG_HTTP_INTERNAL_SERVER_ERROR);
			}
		}
		
		// Output the result
		$output = elgg_view_page($method, elgg_view('api/output', [
			'result' => $result,
		]));
		
		$filename = 'result.' . $request->getParam('view');
		
		$response = elgg_ok_response($output);
		$response->setStatusCode($result->getHttpStatus());
		
		$response->setHeaders([
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
		]);
		
		return $response;
	}
	
	/**
	 * Prepare the Elgg environment for the API request
	 *
	 * @param Request $request current request
	 *
	 * @return void
	 * @throws BadRequestException
	 */
	protected function prepareForRequest(Request $request): void {
		set_exception_handler([$this, 'exceptionHandler']);
		
		if ($request->elgg()->config->debug) {
			// add logger to default elgg logger
			$handler = RestApiErrorHandler::instance();
			
			$request->elgg()->logger->pushHandler($handler);
		}
		
		elgg_set_context('api');
		
		$viewtype = $request->getParam('view', 'json');
		if (!ctype_alpha($viewtype)) {
			throw new BadRequestException('Invalid format');
		}
		
		if (!elgg_view_exists('api/output', $viewtype)) {
			$message = elgg_echo('BadRequestException:MissingOutputViewInViewtype', [$viewtype]);
			if (in_array($viewtype, ['xml', 'php'])) {
				$message .= PHP_EOL . elgg_echo('BadRequestException:MissingOutputViewInViewtype:DataViewsPlugin');
			}
			
			throw new BadRequestException($message);
		}
		
		elgg_set_viewtype($viewtype);
	}
	
	/**
	 * Initialize the API
	 *
	 * @return void
	 */
	protected function initApi(): void {
		// plugins should return true to control what API and user authentication handlers are registered
		if (elgg_trigger_event_results('rest', 'init', [], false) !== false) {
			return;
		}
		
		// user token can also be used for user authentication
		elgg_register_pam_handler(\Elgg\WebServices\PAM\User\AuthToken::class);
		
		// simple API key check
		elgg_register_pam_handler(\Elgg\WebServices\PAM\API\APIKey::class, 'sufficient', 'api');
		
		// hmac
		elgg_register_pam_handler(\Elgg\WebServices\PAM\API\Hmac::class, 'sufficient', 'api');
	}
	
	/**
	 * Check that the method call has the proper API and user authentication
	 *
	 * @param string $method              The api name that was exposed
	 * @param string $http_request_method The HTTP call method (GET|POST|...)
	 *
	 * @return ApiMethod
	 * @throws \APIException
	 */
	protected function authenticateMethod(string $method, string $http_request_method): ApiMethod {
		$api = ApiRegistrationService::instance()->getApiMethod($method, $http_request_method);
		
		// method must be exposed
		if (!$api instanceof ApiMethod) {
			throw new \APIException(elgg_echo('APIException:MethodCallNotImplemented', [$method]));
		}
		
		// check API authentication if required
		if ($api->require_api_auth) {
			try {
				if (!elgg_pam_authenticate('api')) {
					throw new \APIException(elgg_echo('APIException:APIAuthenticationFailed'));
				}
			} catch (AuthenticationException $api_exception) {
				// API authentication failed
				$message = $api_exception->getMessage() ?: elgg_echo('APIException:APIAuthenticationFailed');
				$code = $api_exception->getCode() ?: \ErrorResult::RESULT_FAIL;
				throw new \APIException($message, $code, $api_exception);
			}
		}
		
		// authenticate (and login) user for api call that can handle different results for logged in and out users
		// eg. blog listing
		$user_exception = null;
		try {
			$user_authenticated = elgg_pam_authenticate('user');
		} catch (AuthenticationException $user_exception) {
			// user authentication failed
			$user_authenticated = false;
		}
		
		// check if user authentication is required
		if ($api->require_user_auth && $user_authenticated !== true) {
			$message = elgg_echo('SecurityException:authenticationfailed');
			if ($user_exception instanceof AuthenticationException) {
				$message = $user_exception->getMessage();
			}
			
			throw new \APIException($message, \ErrorResult::RESULT_FAIL_AUTHTOKEN, $user_exception);
		}
		
		return $api;
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
	public function exceptionHandler(\Throwable $throwable): void {
		error_log('*** FATAL EXCEPTION (API) *** : ' . $throwable);
		
		$code = $throwable->getCode() === 0 ? \ErrorResult::RESULT_FAIL : $throwable->getCode();
		$result = new \ErrorResult($throwable->getMessage(), $code);
		
		$output = elgg_view('api/output', [
			'result' => $result,
		]);
		
		$viewtype = elgg_get_viewtype();
		switch ($viewtype) {
			case 'json':
				$response = new JsonResponse($output, ELGG_HTTP_INTERNAL_SERVER_ERROR, ['Content-Type' => 'application/json; charset=utf-8'], true);
				break;
			default:
				$response = new Response($output, ELGG_HTTP_INTERNAL_SERVER_ERROR);
				break;
		}
		
		$response->prepare(_elgg_services()->request);
		_elgg_services()->responseFactory->send($response);
	}
}
