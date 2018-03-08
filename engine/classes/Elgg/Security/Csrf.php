<?php

namespace Elgg\Security;

use Elgg\Config;
use Elgg\CsrfException;
use Elgg\Request;
use Elgg\TimeUsing;
use ElggCrypto;
use ElggSession;

/**
 * CSRF Protection
 */
class Csrf {

	use TimeUsing;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var ElggSession
	 */
	protected $session;

	/**
	 * @var ElggCrypto
	 */
	protected $crypto;

	/**
	 * @var HmacFactory
	 */
	protected $hmac;

	/**
	 * Constructor
	 *
	 * @param Config      $config  Elgg config
	 * @param ElggSession $session Session
	 * @param ElggCrypto  $crypto  Crypto service
	 * @param HmacFactory $hmac    HMAC service
	 */
	public function __construct(
		Config $config,
		ElggSession $session,
		ElggCrypto $crypto,
		HmacFactory $hmac
	) {

		$this->config = $config;
		$this->session = $session;
		$this->crypto = $crypto;
		$this->hmac = $hmac;
	}

	/**
	 * Validate CSRF tokens present in the request
	 *
	 * @param Request $request Request
	 *
	 * @return void
	 * @throws CsrfException
	 */
	public function validate(Request $request) {
		$token = $request->getParam('__elgg_token');
		$ts = $request->getParam('__elgg_ts');

		$session_id = $this->session->getId();

		if (($token) && ($ts) && ($session_id)) {
			if ($this->validateTokenOwnership($token, $ts)) {
				if ($this->validateTokenTimestamp($ts)) {
					// We have already got this far, so unless anything
					// else says something to the contrary we assume we're ok
					$returnval = $request->elgg()->hooks->trigger('action_gatekeeper:permissions:check', 'all', [
						'token' => $token,
						'time' => $ts
					], true);

					if ($returnval) {
						return;
					} else {
						throw new CsrfException($request->elgg()->echo('actiongatekeeper:pluginprevents'));
					}
				} else {
					// this is necessary because of #5133
					if ($request->isXhr()) {
						throw new CsrfException($request->elgg()->echo(
							'js:security:token_refresh_failed',
							[$this->config->wwwroot]
						));
					} else {
						throw new CsrfException($request->elgg()->echo('actiongatekeeper:timeerror'));
					}
				}
			} else {
				// this is necessary because of #5133
				if ($request->isXhr()) {
					throw new CsrfException($request->elgg()->echo('js:security:token_refresh_failed', [$this->config->wwwroot]));
				} else {
					throw new CsrfException($request->elgg()->echo('actiongatekeeper:tokeninvalid'));
				}
			}
		} else {
			$error_msg = $request->elgg()->echo('actiongatekeeper:missingfields');
			throw new CsrfException($request->elgg()->echo($error_msg));
		}
	}

	/**
	 * Basic token validation
	 *
	 * @param string $token Token
	 * @param int    $ts    Timestamp
	 *
	 * @return bool
	 *
	 * @access private
	 * @internal
	 */
	public function isValidToken($token, $ts) {
		return $this->validateTokenOwnership($token, $ts) && $this->validateTokenTimestamp($ts);
	}

	/**
	 * Is the token timestamp within acceptable range?
	 *
	 * @param int $ts timestamp from the CSRF token
	 *
	 * @return bool
	 */
	protected function validateTokenTimestamp($ts) {
		$timeout = $this->getActionTokenTimeout();
		$now = $this->getCurrentTime()->getTimestamp();

		return ($timeout == 0 || ($ts > $now - $timeout) && ($ts < $now + $timeout));
	}

	/**
	 * Returns the action token timeout in seconds
	 *
	 * @return int number of seconds that action token is valid
	 *
	 * @see    Csrf::validateActionToken
	 * @access private
	 * @since  1.9.0
	 */
	public function getActionTokenTimeout() {
		if (($timeout = $this->config->action_token_timeout) === null) {
			// default to 2 hours
			$timeout = 2;
		}
		$hour = 60 * 60;

		return (int) ((float) $timeout * $hour);
	}

	/**
	 * Was the given token generated for the session defined by session_token?
	 *
	 * @param string $token         CSRF token
	 * @param int    $timestamp     Unix time
	 * @param string $session_token Session-specific token
	 *
	 * @return bool
	 * @access private
	 * @inernal
	 */
	public function validateTokenOwnership($token, $timestamp, $session_token = '') {
		$required_token = $this->generateActionToken($timestamp, $session_token);

		return $this->crypto->areEqual($token, $required_token);
	}

	/**
	 * Generate a token from a session token (specifying the user), the timestamp, and the site key.
	 *
	 * @see    generate_action_token()
	 *
	 * @param int    $timestamp     Unix timestamp
	 * @param string $session_token Session-specific token
	 *
	 * @return string
	 * @access private
	 */
	public function generateActionToken($timestamp, $session_token = '') {
		if (!$session_token) {
			$session_token = $this->session->get('__elgg_session');
			if (!$session_token) {
				return false;
			}
		}

		return $this->hmac
			->getHmac([(int) $timestamp, $session_token], 'md5')
			->getToken();
	}

}
