<?php

use Elgg\Exceptions\AuthenticationException;

/**
 * Pluggable Authentication Module
 *
 * @deprecated 4.3 use elgg_pam_authenticate()
 */
class ElggPAM {

	/**
	 * @var string PAM policy type: user, api or plugin-defined policies
	 */
	protected $policy;

	/**
	 * @var string Failure mesages
	 */
	protected $message;

	/**
	 * \ElggPAM constructor
	 *
	 * @param string $policy PAM policy type: user, api, or plugin-defined policies
	 */
	public function __construct($policy) {
		$this->policy = $policy;
	}

	/**
	 * Authenticate a set of credentials against a policy
	 * This function will process all registered PAM handlers or stop when the first
	 * handler fails. A handler fails by either returning false or throwing an
	 * exception. The advantage of throwing an exception is that it returns a message
	 * that can be passed to the user. The processing order of the handlers is
	 * determined by the order that they were registered.
	 *
	 * If $credentials are provided, the PAM handler should authenticate using the
	 * provided credentials. If not, then credentials should be prompted for or
	 * otherwise retrieved (eg from the HTTP header or $_SESSION).
	 *
	 * @param array $credentials Credentials array dependant on policy type
	 * @return bool
	 */
	public function authenticate($credentials = []) {
		
		try {
			return elgg_pam_authenticate($this->policy, $credentials);
		} catch (AuthenticationException $e) {
			$this->message = $e->getMessage();
		}
		
		return false;
	}

	/**
	 * Get a failure message to display to user
	 *
	 * @return string
	 */
	public function getFailureMessage() {
		$message = _elgg_services()->translator->translate('auth:nopams');
		if (!empty($this->message)) {
			$message = $this->message;
		}

		return _elgg_services()->hooks->triggerDeprecated('fail', 'auth', $this->message, $message, "The 'fail', 'auth' hook is deprecated", '4.3');
	}
}
