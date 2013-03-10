<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @since 1.9.0
 * @access private
 */
class Elgg_ActionsService {
	
	/**
	 * Registered actions storage
	 * @var array
	 */
	private $actions = array();
	
	/**
	 * @see action
	 * @access private
	 * @since 1.9.0
	 */
	public function execute($action, $forwarder = "") {
		$action = rtrim($action, '/');
	
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
			action_gatekeeper();
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
	 * @since 1.9.0
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
	 * @since 1.9.0
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
	 * @since 1.9.0
	 */
	public function validateActionToken($visibleerrors = TRUE, $token = NULL, $ts = NULL) {
		if (!$token) {
			$token = get_input('__elgg_token');
		}
	
		if (!$ts) {
			$ts = get_input('__elgg_ts');
		}
	
		if (($timeout = elgg_get_config('action_token_timeout')) === null) {
			// default to 2 hours
			$timeout = 2;
		}

		$session_id = _elgg_services()->session->getId();
	
		if (($token) && ($ts) && ($session_id)) {
			// generate token, check with input and forward if invalid
			$generated_token = generate_action_token($ts);
	
			// Validate token
			if ($token == $generated_token) {
				$hour = 60 * 60;
				$timeout = $timeout * $hour;
				$now = time();
	
				// Validate time to ensure its not crazy
				if ($timeout == 0 || ($ts > $now - $timeout) && ($ts < $now + $timeout)) {
					// We have already got this far, so unless anything
					// else says something to the contry we assume we're ok
					$returnval = true;
	
					$returnval = elgg_trigger_plugin_hook('action_gatekeeper:permissions:check', 'all', array(
						'token' => $token,
						'time' => $ts
					), $returnval);
	
					if ($returnval) {
						return true;
					} else if ($visibleerrors) {
						register_error(elgg_echo('actiongatekeeper:pluginprevents'));
					}
				} else if ($visibleerrors) {
					// this is necessary because of #5133
					if (elgg_is_xhr()) {
						register_error(elgg_echo('js:security:token_refresh_failed', array(elgg_get_site_url())));
					} else {
						register_error(elgg_echo('actiongatekeeper:timeerror'));
					}
				}
			} else if ($visibleerrors) {
				// this is necessary because of #5133
				if (elgg_is_xhr()) {
					register_error(elgg_echo('js:security:token_refresh_failed', array(elgg_get_site_url())));
				} else {
					register_error(elgg_echo('actiongatekeeper:tokeninvalid'));
				}
			}
		} else {
			if (! empty($_SERVER['CONTENT_LENGTH']) && empty($_POST)) {
				// The size of $_POST or uploaded file has exceed the size limit
				$error_msg = elgg_trigger_plugin_hook('action_gatekeeper:upload_exceeded_msg', 'all', array(
					'post_size' => $_SERVER['CONTENT_LENGTH'],
					'visible_errors' => $visibleerrors,
				), elgg_echo('actiongatekeeper:uploadexceeded'));
			} else {
				$error_msg = elgg_echo('actiongatekeeper:missingfields');
			}
			if ($visibleerrors) {
				register_error($error_msg);
			}
		}
	
		return FALSE;
	}
	
	/**
	 * @see action_gatekeeper
	 * @access private
	 * @since 1.9.0
	 */
	public function gatekeeper() {
		if ($this->validateActionToken()) {
			return TRUE;
		}
	
		forward(REFERER, 'csrf');
	}
	
	/**
	 * @see generate_action_token
	 * @access private
	 * @since 1.9.0
	 */
	public function generateActionToken($timestamp) {
		$site_secret = get_site_secret();
		$session_id = _elgg_services()->session->getId();
		// Session token
		$st = _elgg_services()->session->get('__elgg_session');
	
		if (($site_secret) && ($session_id)) {
			return md5($site_secret . $timestamp . $session_id . $st);
		}
	
		return FALSE;
	}
	
	/**
	 * @see elgg_action_exists
	 * @access private
	 * @since 1.9.0
	 */
	public function exists($action) {
		return (isset($this->actions[$action]) && file_exists($this->actions[$action]['file']));
	}
	
	/**
	 * @see ajax_forward_hook
	 * @access private
	 * @since 1.9.0
	 */
	public function ajaxForwardHook($hook, $type, $reason, $params) {
		if (elgg_is_xhr()) {
			// always pass the full structure to avoid boilerplate JS code.
			$params = array(
				'output' => '',
				'status' => 0,
				'system_messages' => array(
					'error' => array(),
					'success' => array()
				)
			);
	
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
			$system_messages = system_messages(NULL, "");
	
			if (isset($system_messages['success'])) {
				$params['system_messages']['success'] = $system_messages['success'];
			}
	
			if (isset($system_messages['error'])) {
				$params['system_messages']['error'] = $system_messages['error'];
				$params['status'] = -1;
			}
	
			// Check the requester can accept JSON responses, if not fall back to
			// returning JSON in a plain-text response.  Some libraries request
			// JSON in an invisible iframe which they then read from the iframe,
			// however some browsers will not accept the JSON MIME type.
			if (stripos($_SERVER['HTTP_ACCEPT'], 'application/json') === FALSE) {
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
	 * @since 1.9.0
	 */
	public function ajaxActionHook() {
		if (elgg_is_xhr()) {
			ob_start();
		}
	}
}
