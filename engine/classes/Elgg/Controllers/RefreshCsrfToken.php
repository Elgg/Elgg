<?php

namespace Elgg\Controllers;

use Symfony\Component\HttpFoundation\Response;

/**
 * Handles requests to /refresh_token
 *
 * @internal
 */
class RefreshCsrfToken {

	/**
	 * Send an updated CSRF token, provided the page's current tokens were not fake.
	 *
	 * @param \Elgg\Http\Request $request Request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function __invoke(\Elgg\Http\Request $request) {

		_elgg_session_boot(_elgg_services());
		
		// the page's session_token might have expired (not matching __elgg_session in the session), but
		// we still allow it to be given to validate the tokens in the page.
		$session_token = get_input('session_token', null, false);
		$pairs = (array) get_input('pairs', [], false);
		$valid_tokens = (object) [];
		
		foreach ($pairs as $pair) {
			list($ts, $token) = explode(',', $pair, 2);
			if (_elgg_services()->csrf->validateTokenOwnership($token, (int) $ts, $session_token)) {
				$valid_tokens->{$token} = true;
			}
		}

		$ts = _elgg_services()->csrf->getCurrentTime()->getTimestamp();
		$token = _elgg_services()->csrf->generateActionToken($ts);
		
		$data = [
			'token' => [
				'__elgg_ts' => $ts,
				'__elgg_token' => $token,
				'logged_in' => _elgg_services()->session->isLoggedIn(),
			],
			'valid_tokens' => $valid_tokens,
			'session_token' => _elgg_services()->session->get('__elgg_session'),
			'user_guid' => _elgg_services()->session->getLoggedInUserGuid(),
		];

		$response = Response::create();
		$response->headers->set('Content-Type', "application/json;charset=utf-8", true);
		$response->headers->set('X-Content-Type-Options', 'nosniff', true);
		
		return $response->setContent(json_encode($data));
	}

}
