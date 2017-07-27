<?php

/**
 * Pluggable Authentication Module
 */
class ElggPAM {

	/**
	 * @var array
	 * @access private
	 * @todo move state into a PAM service
	 */
	public static $_handlers = [];

	/**
	 * @var string PAM policy type: user, api or plugin-defined policies
	 */
	protected $policy;

	/**
	 * @var array Failure mesages
	 */
	protected $messages;

	/**
	 * \ElggPAM constructor
	 *
	 * @param string $policy PAM policy type: user, api, or plugin-defined policies
	 */
	public function __construct($policy) {
		$this->policy = $policy;
		$this->messages = ['sufficient' => [], 'required' => []];
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
		if (!isset(self::$_handlers[$this->policy]) ||
			!is_array(self::$_handlers[$this->policy])) {
			return false;
		}

		$authenticated = false;

		foreach (self::$_handlers[$this->policy] as $v) {
			$handler = $v->handler;
			if (!is_callable($handler)) {
				continue;
			}
			/* @var callable $handler */

			$importance = $v->importance;

			try {
				// Execute the handler
				// @todo don't assume $handler is a global function
				$result = call_user_func($handler, $credentials);
				if ($result) {
					$authenticated = true;
				} elseif ($result === false) {
					if ($importance == 'required') {
						$this->messages['required'][] = "$handler:failed";
						return false;
					} else {
						$this->messages['sufficient'][] = "$handler:failed";
					}
				}
			} catch (Exception $e) {
				if ($importance == 'required') {
					$this->messages['required'][] = $e->getMessage();
					return false;
				} else {
					$this->messages['sufficient'][] = $e->getMessage();
				}
			}
		}

		return $authenticated;
	}

	/**
	 * Get a failure message to display to user
	 *
	 * @return string
	 */
	public function getFailureMessage() {
		$message = _elgg_services()->translator->translate('auth:nopams');
		if (!empty($this->messages['required'])) {
			$message = $this->messages['required'][0];
		} elseif (!empty($this->messages['sufficient'])) {
			$message = $this->messages['sufficient'][0];
		}

		return _elgg_services()->hooks->trigger('fail', 'auth', $this->messages, $message);
	}
}
