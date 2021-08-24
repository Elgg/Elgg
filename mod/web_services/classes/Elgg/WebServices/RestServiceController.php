<?php

namespace Elgg\WebServices;

use Elgg\Request;
use Elgg\Http\ResponseBuilder;
use Elgg\WebServices\Di\ApiRegistrationService;

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
	public function __invoke(Request $request) {
		// plugins should return true to control what API and user authentication handlers are registered
		if (elgg_trigger_plugin_hook('rest', 'init', null, false) === false) {
			// user token can also be used for user authentication
			register_pam_handler('elgg_ws_pam_auth_usertoken');
			
			// simple API key check
			if (elgg_get_plugin_setting('auth_allow_key', 'web_services')) {
				register_pam_handler('elgg_ws_pam_auth_api_key', 'sufficient', 'api');
			}
			// hmac
			if (elgg_get_plugin_setting('auth_allow_hmac', 'web_services')) {
				register_pam_handler('elgg_ws_pam_auth_api_hmac', 'sufficient', 'api');
			}
		}
		
		// Get parameter variables
		$method = $request->getParam('method');
		
		// this will throw an exception if authentication fails
		$api = $this->authenticateMethod($method);
		
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
	 * @param string $method The api name that was exposed
	 *
	 * @return ApiMethod
	 * @throws \APIException
	 */
	protected function authenticateMethod($method) {
		$api = ApiRegistrationService::instance()->getApiMethod($method);
		
		// method must be exposed
		if (!$api instanceof ApiMethod) {
			throw new \APIException(elgg_echo('APIException:MethodCallNotImplemented', [$method]));
		}
		
		// check API authentication if required
		if ($api->require_api_auth) {
			$api_pam = new \ElggPAM('api');
			if ($api_pam->authenticate() !== true) {
				throw new \APIException(elgg_echo('APIException:APIAuthenticationFailed'));
			}
		}
		
		// authenticate (and login) user for aip call that can handle different results for logged in and out users
		// eg. blog listing
		$user_pam = new \ElggPAM('user');
		$user_auth_result = $user_pam->authenticate([]);
		
		// check if user authentication is required
		if ($api->require_user_auth) {
			if (!$user_auth_result) {
				throw new \APIException($user_pam->getFailureMessage(), \ErrorResult::$RESULT_FAIL_AUTHTOKEN);
			}
		}
		
		return $api;
	}
}
