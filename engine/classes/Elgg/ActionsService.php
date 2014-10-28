<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Actions
 * @since      1.9.0
 */
class Elgg_ActionsService {

	/**
	 * Registered actions storage
	 * @var array
	 */
	private $actions = array();

	/**
	 * The current action being processed
	 * @var string
	 */
	private $currentAction = null;

	/**
	 * @see action
	 * @access private
	 */
	public function execute($action, $forwarder = "") {
		$action = rtrim($action, '/');
		$this->currentAction = $action;

		// @todo REMOVE THESE ONCE #1509 IS IN PLACE.
		// Allow users to disable plugins without a token in order to
		// remove plugins that are incompatible.
		// Login and logout are for convenience.
		// file/download (see #2010)
		$exceptions = array(
			'admin/plugins/disable',
			'logout',
			'file/download',
		);

		if (!in_array($action, $exceptions)) {
			// All actions require a token.
			action_gatekeeper($action);
		}

		$forwarder = str_replace(elgg_get_site_url(), "", $forwarder);
		$forwarder = str_replace("http://", "", $forwarder);
		$forwarder = str_replace("@", "", $forwarder);
		if (substr($forwarder, 0, 1) == "/") {
			$forwarder = substr($forwarder, 1);
		}

		if (!isset($this->actions[$action])) {
			register_error(elgg_echo('actionundefined', array($action)));
		} elseif (!elgg_is_admin_logged_in() && ($this->actions[$action]['access'] === 'admin')) {
			register_error(elgg_echo('actionunauthorized'));
		} elseif (!elgg_is_logged_in() && ($this->actions[$action]['access'] !== 'public')) {
			register_error(elgg_echo('actionloggedout'));
		} else {
			// Returning falsy doesn't produce an error
			// We assume this will be handled in the hook itself.
			if (elgg_trigger_plugin_hook('action', $action, null, true)) {
				if (!include($this->actions[$action]['file'])) {
					register_error(elgg_echo('actionnotfound', array($action)));
				}
			}
		}

		$forwarder = empty($forwarder) ? REFERER : $forwarder;
		forward($forwarder);
	}

	/**
	 * @see elgg_register_action
	 * @access private
	 */
	public function register($action, $filename = "", $access = 'logged_in') {
		// plugins are encouraged to call actions with a trailing / to prevent 301
		// redirects but we store the actions without it
		$action = rtrim($action, '/');

		if (empty($filename)) {

			$path = elgg_get_config('path');
			if ($path === null) {
				$path = "";
			}

			$filename = $path . "actions/" . $action . ".php";
		}

		$this->actions[$action] = array(
			'file' => $filename,
			'access' => $access,
		);
		return true;
	}

	/**
	 * @see elgg_unregister_action
	 * @access private
	 */
	public function unregister($action) {
		if (isset($this->actions[$action])) {
			unset($this->actions[$action]);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @see validate_action_token
	 * @access private
	 */
	public function validateActionToken($visible_errors = true, $token = null, $ts = null) {
		if (!$token) {
			$token = get_input('__elgg_token');
		}

		if (!$ts) {
			$ts = get_input('__elgg_ts');
		}

		$session_id = _elgg_services()->session->getId();

		if (($token) && ($ts) && ($session_id)) {
			// generate token, check with input and forward if invalid
			$required_token = generate_action_token($ts);

			// Validate token
			if ($token == $required_token) {
				if ($this->validateTokenTimestamp($ts)) {
					// We have already got this far, so unless anything
					// else says something to the contrary we assume we're ok
					$returnval = elgg_trigger_plugin_hook('action_gatekeeper:permissions:check', 'all', array(
						'token' => $token,
						'time' => $ts
					), true);

					if ($returnval) {
						return true;
					} else if ($visible_errors) {
						register_error(elgg_echo('actiongatekeeper:pluginprevents'));
					}
				} else if ($visible_errors) {
					// this is necessary because of #5133
					if (elgg_is_xhr()) {
						register_error(elgg_echo('js:security:token_refresh_failed', array(elgg_get_site_url())));
					} else {
						register_error(elgg_echo('actiongatekeeper:timeerror'));
					}
				}
			} else if ($visible_errors) {
				// this is necessary because of #5133
				if (elgg_is_xhr()) {
					register_error(elgg_echo('js:security:token_refresh_failed', array(elgg_get_site_url())));
				} else {
					register_error(elgg_echo('actiongatekeeper:tokeninvalid'));
				}
			}
		} else {
			$req = _elgg_services()->request;
			$length = $req->server->get('CONTENT_LENGTH');
			$post_count = count($req->request);
			if ($length && $post_count < 1) {
				// The size of $_POST or uploaded file has exceed the size limit
				$error_msg = elgg_trigger_plugin_hook('action_gatekeeper:upload_exceeded_msg', 'all', array(
					'post_size' => $length,
					'visible_errors' => $visible_errors,
				), elgg_echo('actiongatekeeper:uploadexceeded'));
			} else {
				$error_msg = elgg_echo('actiongatekeeper:missingfields');
			}
			if ($visible_errors) {
				register_error($error_msg);
			}
		}

		return false;
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
		$now = time();
		return ($timeout == 0 || ($ts > $now - $timeout) && ($ts < $now + $timeout));
	}

	/**
	 * @see Elgg_ActionsService::validateActionToken
	 * @access private
	 * @since 1.9.0
	 * @return int number of seconds that action token is valid
	 */
	public function getActionTokenTimeout() {
		if (($timeout = elgg_get_config('action_token_timeout')) === null) {
			// default to 2 hours
			$timeout = 2;
		}
		$hour = 60 * 60;
		return (int)((float)$timeout * $hour);
	}

	/**
	 * @see action_gatekeeper
	 * @access private
	 */
	public function gatekeeper($action) {
		if ($action === 'login') {
			if ($this->validateActionToken(false)) {
				return true;
			}

			$token = get_input('__elgg_token');
			$ts = (int)get_input('__elgg_ts');
			if ($token && $this->validateTokenTimestamp($ts)) {
				// The tokens are present and the time looks valid: this is probably a mismatch due to the
				// login form being on a different domain.
				register_error(elgg_echo('actiongatekeeper:crosssitelogin'));

				forward('login', 'csrf');
			}

			// let the validator send an appropriate msg
			validate_action_token();
		} else if ($this->validateActionToken()) {
			return true;
		}

		forward(REFERER, 'csrf');
	}

	/**
	 * @see generate_action_token
	 * @access private
	 */
	public function generateActionToken($timestamp) {
		$site_secret = get_site_secret();
		$session_id = _elgg_services()->session->getId();
		// Session token
		$st = _elgg_services()->session->get('__elgg_session');

		if (($site_secret) && ($session_id)) {
			return md5($site_secret . $timestamp . $session_id . $st);
		}

		return false;
	}

	/**
	 * @see elgg_action_exists
	 * @access private
	 */
	public function exists($action) {
		return (isset($this->actions[$action]) && file_exists($this->actions[$action]['file']));
	}

	/**
	 * @see ajax_forward_hook
	 * @access private
	 */
	public function ajaxForwardHook($hook, $reason, $return, $params) {
		if (elgg_is_xhr()) {
			// always pass the full structure to avoid boilerplate JS code.
			$params = array_merge($params, array(
				'output' => '',
				'status' => 0,
				'system_messages' => array(
					'error' => array(),
					'success' => array()
				)
			));

			//grab any data echo'd in the action
			$output = ob_get_clean();

			//Avoid double-encoding in case data is json
			$json = json_decode($output);
			if (isset($json)) {
				$params['output'] = $json;
			} else {
				$params['output'] = $output;
			}

			//Grab any system messages so we can inject them via ajax too
			$system_messages = system_messages(null, "");

			if (isset($system_messages['success'])) {
				$params['system_messages']['success'] = $system_messages['success'];
			}

			if (isset($system_messages['error'])) {
				$params['system_messages']['error'] = $system_messages['error'];
				$params['status'] = -1;
			}

			$context = array('action' => $this->currentAction);
			$params = elgg_trigger_plugin_hook('output', 'ajax', $context, $params);

			// Check the requester can accept JSON responses, if not fall back to
			// returning JSON in a plain-text response.  Some libraries request
			// JSON in an invisible iframe which they then read from the iframe,
			// however some browsers will not accept the JSON MIME type.
			$http_accept = _elgg_services()->request->server->get('HTTP_ACCEPT');
			if (stripos($http_accept, 'application/json') === false) {
				header("Content-type: text/plain");
			} else {
				header("Content-type: application/json");
			}

			echo json_encode($params);
			exit;
		}
	}

	/**
	 * @see ajax_action_hook
	 * @access private
	 */
	public function ajaxActionHook() {
		if (elgg_is_xhr()) {
			ob_start();
		}
	}

	/**
	 * Get all actions
	 *
	 * @return array
	 */
	public function getAllActions() {
		return $this->actions;
	}
}
