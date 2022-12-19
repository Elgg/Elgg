<?php

namespace Elgg\WebServices;

use Elgg\Request;
use Elgg\Http\ResponseBuilder;
use Elgg\WebServices\Di\ApiRegistrationService;
use Elgg\Exceptions\AuthenticationException;

/**
 * Handle /services/api/rest/... calls
 *
 * @since 4.0
 */
class RestServiceController {
	
	/**
	 * Handle a HTTP request
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request): ResponseBuilder {
		// plugins should return true to control what API and user authentication handlers are registered
		if (elgg_trigger_event_results('rest', 'init', [], false) === false) {
			// user token can also be used for user authentication
			elgg_register_pam_handler(\Elgg\WebServices\PAM\User\AuthToken::class);
			
			// simple API key check
			if (elgg_get_plugin_setting('auth_allow_key', 'web_services')) {
				elgg_register_pam_handler(\Elgg\WebServices\PAM\API\APIKey::class, 'sufficient', 'api');
			}
			
			// hmac
			if (elgg_get_plugin_setting('auth_allow_hmac', 'web_services')) {
				elgg_register_pam_handler(\Elgg\WebServices\PAM\API\Hmac::class, 'sufficient', 'api');
			}
		}
		
		// Get parameter variables
		$method = (string) $request->getParam('method');
		
		// this will throw an exception if authentication fails
		$api = $this->authenticateMethod($method, $request->getMethod());
		
		// execute the api method
		$result = $api->execute($request);
		
		// Output the result
		$output = elgg_view_page($method, elgg_view('api/output', [
			'result' => $result,
		]));
		
		return elgg_ok_response($output);
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
			
			throw new \APIException($message, \ErrorResult::RESULT_FAIL_AUTHTOKEN);
		}
		
		return $api;
	}
}
