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
	 * Perform an action.
	 *
	 * This function executes the action with name $action as registered
	 * by {@link elgg_register_action()}.
	 *
	 * The plugin hook 'action', $action_name will be triggered before the action
	 * is executed.  If a handler returns false, it will prevent the action script
	 * from being called.
	 *
	 * @note If an action isn't registered in the system or is registered
	 * to an unavailable file the user will be forwarded to the site front
	 * page and an error will be emitted via {@link register_error()}.
	 *
	 * @warning All actions require {@link http://docs.elgg.org/Actions/Tokens Action Tokens}.
	 *
	 * @param string $action    The requested action
	 * @param string $forwarder Optionally, the location to forward to
	 *
	 * @link http://docs.elgg.org/Actions
	 * @see elgg_register_action()
	 *
	 * @return void
	 * @access private
	 * @since 1.9.0
	 */
	function execute($action, $forwarder = "") {
		$action = rtrim($action, '/');
	
		// @todo REMOVE THESE ONCE #1509 IS IN PLACE.
		// Allow users to disable plugins without a token in order to
		// remove plugins that are incompatible.
		// Login and logout are for convenience.
		// file/download (see #2010)
		$exceptions = array(
			'admin/plugins/disable',
			'logout',
			'login',
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
	 * Registers an action.
	 *
	 * Actions are registered to a script in the system and are executed
	 * either by the URL http://elggsite.org/action/action_name/.
	 *
	 * $filename must be the full path of the file to register, or a path relative
	 * to the core actions/ dir.
	 *
	 * Actions should be namedspaced for your plugin.  Example:
	 * <code>
	 * elgg_register_action('myplugin/save_settings', ...);
	 * </code>
	 *
	 * @tip Put action files under the actions/<plugin_name> directory of your plugin.
	 *
	 * @tip You don't need to include engine/start.php in your action files.
	 *
	 * @internal Actions are saved in $this->actions as an array in the form:
	 * <code>
	 * array(
	 * 	'file' => '/location/to/file.php',
	 * 	'access' => 'public', 'logged_in', or 'admin'
	 * )
	 * </code>
	 *
	 * @param string $action   The name of the action (eg "register", "account/settings/save")
	 * @param string $filename Optionally, the filename where this action is located. If not specified,
	 *                         will assume the action is in elgg/actions/<action>.php
	 * @param string $access   Who is allowed to execute this action: public, logged_in, admin.
	 *                         (default: logged_in)
	 *
	 * @see action()
	 * @see http://docs.elgg.org/Actions
	 *
	 * @return bool
	 * @since 1.9.0
	 */
	function register($action, $filename = "", $access = 'logged_in') {
		// plugins are encouraged to call actions with a trailing / to prevent 301
		// redirects but we store the actions without it
		$action = rtrim($action, '/');
	
		if (!isset($this->actions)) {
			$this->actions = array();
		}
	
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
	 * Unregisters an action
	 *
	 * @param string $action Action name
	 * @return bool
	 * @since 1.9.0
	 */
	function unregister($action) {
		if (isset($this->actions[$action])) {
			unset($this->actions[$action]);
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Validate an action token.
	 *
	 * Calls to actions will automatically validate tokens. If tokens are not
	 * present or invalid, the action will be denied and the user will be redirected.
	 *
	 * Plugin authors should never have to manually validate action tokens.
	 *
	 * @param bool  $visibleerrors Emit {@link register_error()} errors on failure?
	 * @param mixed $token         The token to test against. Default: $_REQUEST['__elgg_token']
	 * @param mixed $ts            The time stamp to test against. Default: $_REQUEST['__elgg_ts']
	 *
	 * @return bool
	 * @see generate_action_token()
	 * @link http://docs.elgg.org/Actions/Tokens
	 * @access private
	 * @since 1.9.0
	 */
	function validateActionToken($visibleerrors = TRUE, $token = NULL, $ts = NULL) {
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
	
		$session_id = session_id();
	
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
					register_error(elgg_echo('actiongatekeeper:timeerror'));
				}
			} else if ($visibleerrors) {
				register_error(elgg_echo('actiongatekeeper:tokeninvalid'));
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
	 * Validates the presence of action tokens.
	 *
	 * This function is called for all actions.  If action tokens are missing,
	 * the user will be forwarded to the site front page and an error emitted.
	 *
	 * This function verifies form input for security features (like a generated token),
	 * and forwards if they are invalid.
	 *
	 * @return mixed True if valid or redirects.
	 * @access private
	 * @since 1.9.0
	 */
	function gatekeeper() {
		if ($this->validateActionToken()) {
			return TRUE;
		}
	
		forward(REFERER, 'csrf');
	}
	
	/**
	 * Generate an action token.
	 *
	 * Action tokens are based on timestamps as returned by {@link time()}.
	 * They are valid for one hour.
	 *
	 * Action tokens should be passed to all actions name __elgg_ts and __elgg_token.
	 *
	 * @warning Action tokens are required for all actions.
	 *
	 * @param int $timestamp Unix timestamp
	 *
	 * @see @elgg_view input/securitytoken
	 * @see @elgg_view input/form
	 * @example actions/manual_tokens.php
	 *
	 * @return string|false
	 * @access private
	 * @since 1.9.0
	 */
	function generateActionToken($timestamp) {
		$site_secret = get_site_secret();
		$session_id = session_id();
		// Session token
		$st = $_SESSION['__elgg_session'];
	
		if (($site_secret) && ($session_id)) {
			return md5($site_secret . $timestamp . $session_id . $st);
		}
	
		return FALSE;
	}
	
	/**
	 * Check if an action is registered and its script exists.
	 *
	 * @param string $action Action name
	 *
	 * @return bool
	 * @since 1.9.0
	 */
	function exists($action) {
		return (isset($this->actions[$action]) && file_exists($this->actions[$action]['file']));
	}
	
	/**
	 * Catch calls to forward() in ajax request and force an exit.
	 *
	 * Forces response is json of the following form:
	 * <pre>
	 * {
	 *     "current_url": "the.url.we/were/coming/from",
	 *     "forward_url": "the.url.we/were/going/to",
	 *     "system_messages": {
	 *         "messages": ["msg1", "msg2", ...],
	 *         "errors": ["err1", "err2", ...]
	 *     },
	 *     "status": -1 //or 0 for success if there are no error messages present
	 * }
	 * </pre>
	 * where "system_messages" is all message registers at the point of forwarding
	 *
	 * @param string $hook
	 * @param string $type
	 * @param string $reason
	 * @param array $params
	 * @return void
	 * @access private
	 * @since 1.9.0
	 */
	function ajaxForwardHook($hook, $type, $reason, $params) {
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
	 * Buffer all output echo'd directly in the action for inclusion in the returned JSON.
	 * @return void
	 * @access private
	 * @since 1.9.0
	 */
	function ajaxActionHook() {
		if (elgg_is_xhr()) {
			ob_start();
		}
	}
}
