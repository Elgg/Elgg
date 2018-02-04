<?php

namespace Elgg\Controllers;

use Elgg\Http\ResponseBuilder;
use Elgg\TimeUsing;

/**
 * Handles requests to /refresh_token
 *
 * @access private
 * @internal
 */
class RefreshCsrfToken {

	use TimeUsing;

	/**
	 * Send an updated CSRF token, provided the page's current tokens were not fake.
	 *
	 * @param \Elgg\Request $request Request
	 * @return ResponseBuilder
	 */
	public function __invoke(\Elgg\Request $request) {

		// the page's session_token might have expired (not matching __elgg_session in the session), but
		// we still allow it to be given to validate the tokens in the page.
		$session_token = get_input('session_token', null, false);
		$pairs = (array) get_input('pairs', [], false);
		$valid_tokens = (object) [];
		foreach ($pairs as $pair) {
			list($ts, $token) = explode(',', $pair, 2);
			if ($request->elgg()->csrf->validateTokenOwnership($token, $ts, $session_token)) {
				$valid_tokens->{$token} = true;
			}
		}

		$ts = $this->getCurrentTime()->getTimestamp();
		$token = $request->elgg()->csrf->generateActionToken($ts);
		$data = [
			'token' => [
				'__elgg_ts' => $ts,
				'__elgg_token' => $token,
				'logged_in' => $request->elgg()->session->isLoggedIn(),
			],
			'valid_tokens' => $valid_tokens,
			'session_token' => $request->elgg()->session->get('__elgg_session'),
			'user_guid' => $request->elgg()->session->getLoggedInUserGuid(),
		];

		elgg_set_http_header("Content-Type: application/json;charset=utf-8");

		return elgg_ok_response($data);
	}

}
