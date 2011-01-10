<?php
/**
 * ElggPAM Pluggable Authentication Module
 *
 * @package    Elgg.Core
 * @subpackage Authentication
 */
class ElggPAM {
	/**
	 * @var string PAM policy type: user, api or plugin-defined policies
	 */
	protected $policy;

	/**
	 * @var array Failure mesages
	 */
	protected $messages;

	/**
	 * ElggPAM constructor
	 * 
	 * @param string $policy PAM policy type: user, api, or plugin-defined policies
	 */
	public function __construct($policy) {
		$this->policy = $policy;
		$this->messages = array('sufficient' => array(), 'required' => array());
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
	public function authenticate($credentials = array()) {
		global $_PAM_HANDLERS;

		if (!isset($_PAM_HANDLERS[$this->policy]) ||
			!is_array($_PAM_HANDLERS[$this->policy])) {
			return false;
		}

		$authenticated = false;

		foreach ($_PAM_HANDLERS[$this->policy] as $k => $v) {
			$handler = $v->handler;
			$importance = $v->importance;

			try {
				// Execute the handler
				$result = $handler($credentials);
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
		$message = elgg_echo('auth:nopams');
		if (!empty($this->messages['required'])) {
			$message = $this->messages['required'][0];
		} elseif (!empty($this->messages['sufficient'])) {
			$message = $this->messages['sufficient'][0];
		}

		return elgg_trigger_plugin_hook('fail', 'auth', $this->messages, $message);
	}
}
