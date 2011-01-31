<?php
/**
 * Elgg actions
 * Allows system modules to specify actions
 *
 * @package Elgg
 * @subpackage Core
 */

// Action setting and run *************************************************

/**
* Loads an action script, if it exists, then forwards elsewhere
*
* @param string $action The requested action
* @param string $forwarder Optionally, the location to forward to
*/
function action($action, $forwarder = "") {
	global $CONFIG;

	$action = rtrim($action, '/');

	// @todo REMOVE THESE ONCE #1509 IS IN PLACE.
	// Allow users to disable plugins without a token in order to
	// remove plugins that are incompatible.
	// Installation cannot use tokens because it requires site secret to be
	// working. (#1462)
	// Login and logout are for convenience.
	// file/download (see #2010)
	$exceptions = array(
		'systemsettings/install',
		'admin/plugins/disable',
		'logout',
		'login',
		'file/download',
	);

	if (!in_array($action, $exceptions)) {
		// All actions require a token.
		action_gatekeeper();
	}

	$forwarder = str_replace($CONFIG->url, "", $forwarder);
	$forwarder = str_replace("http://", "", $forwarder);
	$forwarder = str_replace("@", "", $forwarder);

	if (substr($forwarder, 0, 1) == "/") {
		$forwarder = substr($forwarder, 1);
	}

	if (isset($CONFIG->actions[$action])) {
		if ((isadminloggedin()) || (!$CONFIG->actions[$action]['admin'])) {
			if ($CONFIG->actions[$action]['public'] || get_loggedin_userid()) {

				// Trigger action event
				// @todo This is only called before the primary action is called. We need to rethink actions for 1.5
				$event_result = true;
				$event_result = trigger_plugin_hook('action', $action, null, $event_result);

				// Include action
				// Event_result being false doesn't produce an error -
				// since i assume this will be handled in the hook itself.
				// @todo make this better!
				if ($event_result) {
					if (!include($CONFIG->actions[$action]['file'])) {
						register_error(sprintf(elgg_echo('actionundefined'),$action));
					}
				}
			} else {
				register_error(elgg_echo('actionloggedout'));
			}
		} else {
			register_error(elgg_echo('actionunauthorized'));
		}
	} else {
		register_error(sprintf(elgg_echo('actionundefined'),$action));
	}

	forward($CONFIG->url . $forwarder);
}

/**
 * Registers a particular action in memory
 *
 * @param string $action The name of the action (eg "register", "account/settings/save")
 * @param boolean $public Can this action be accessed by people not logged into the system?
 * @param string $filename Optionally, the filename where this action is located
 * @param boolean $admin_only Whether this action is only available to admin users.
 */
function register_action($action, $public = false, $filename = "", $admin_only = false) {
	global $CONFIG;

	// plugins are encouraged to call actions with a trailing / to prevent 301
	// redirects but we store the actions without it
	$action = rtrim($action, '/');

	if (!isset($CONFIG->actions)) {
		$CONFIG->actions = array();
	}

	if (empty($filename)) {
		$path = "";
		if (isset($CONFIG->path)) {
			$path = $CONFIG->path;
		}

		$filename = $path . "actions/" . $action . ".php";
	}

	$CONFIG->actions[$action] = array('file' => $filename, 'public' => $public, 'admin' => $admin_only);
	return true;
}

/**
 * Actions to perform on initialisation
 *
 * @param string $event Events API required parameters
 * @param string $object_type Events API required parameters
 * @param string $object Events API required parameters
 */
function actions_init($event, $object_type, $object) {
	register_action("error");
	return true;
}

/**
 * Validate an action token, returning true if valid and false if not
 *
 * @return unknown
 */
function validate_action_token($visibleerrors = TRUE, $token = NULL, $ts = NULL) {
	global $CONFIG;

	if (!$token) {
		$token = get_input('__elgg_token');
	}

	if (!$ts) {
		$ts = get_input('__elgg_ts');
	}

	if (!isset($CONFIG->action_token_timeout)) {
		// default to 2 hours
		$timeout = 2;
	} else {
		$timeout = $CONFIG->action_token_timeout;
	}

	$session_id = session_id();

	if (($token) && ($ts) && ($session_id)) {
		// generate token, check with input and forward if invalid
		$generated_token = generate_action_token($ts);

		// Validate token
		if ($token == $generated_token) {
			$hour = 60*60;
			$timeout = $timeout * $hour;
			$now = time();

			// Validate time to ensure its not crazy
			if ($timeout == 0 || ($ts>$now-$timeout) && ($ts<$now+$timeout)) {
				// We have already got this far, so unless anything
				// else says something to the contry we assume we're ok
				$returnval = true;

				$returnval = trigger_plugin_hook('action_gatekeeper:permissions:check', 'all', array(
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
	}
	else if ($visibleerrors) {
		register_error(elgg_echo('actiongatekeeper:missingfields'));
	}

	return FALSE;
}

/**
* Action gatekeeper.
* This function verifies form input for security features (like a generated token), and forwards
* the page if they are invalid.
*
*/
function action_gatekeeper() {
	if (validate_action_token()) {
		return TRUE;
	}

	forward(REFERER);
	exit;
}

/**
 * Generate a token for the current user suitable for being placed in a hidden field in action forms.
 *
 * @param int $timestamp Unix timestamp
 */
function generate_action_token($timestamp) {
	// Get input values
	$site_secret = get_site_secret();

	// Current session id
	$session_id = session_id();

	// Session token
	$st = $_SESSION['__elgg_session'];

	if (($site_secret) && ($session_id)) {
		return md5($site_secret.$timestamp.$session_id.$st);
	}

	return FALSE;
}

/**
 * Initialise the site secret.
 *
 */
function init_site_secret() {
	$secret = md5(rand().microtime());
	if (datalist_set('__site_secret__', $secret)) {
		return $secret;
	}

	return FALSE;
}

/**
 * Retrieve the site secret.
 *
 */
function get_site_secret() {
	$secret = datalist_get('__site_secret__');
	if (!$secret) {
		$secret = init_site_secret();
	}

	return $secret;
}


register_elgg_event_handler("init","system","actions_init");
