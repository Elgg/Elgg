<?php
/**
 * Elgg Actions
 *
 * @see http://learn.elgg.org/en/stable/guides/actions.html
 *
 * @package Elgg.Core
 * @subpackage Actions
 */

use Elgg\Database\SiteSecret;
use Elgg\Http\ResponseBuilder;

/**
 * Registers an action.
 *
 * Actions are registered to a script in the system and are executed
 * by the URL http://elggsite.org/action/action_name/.
 *
 * $filename must be the full path of the file to register or a path relative
 * to the core actions/ dir.
 *
 * Actions should be namedspaced for your plugin.  Example:
 * <code>
 * elgg_register_action('myplugin/save_settings', ...);
 * </code>
 *
 * @tip Put action files under the actions/<plugin_name> directory of your plugin.
 *
 * @tip You don't need to use Elgg\Application in your action files.
 *
 * @param string $action   The name of the action (eg "register", "account/settings/save")
 * @param string $filename Optionally, the filename where this action is located. If not specified,
 *                         will assume the action is in elgg/actions/<action>.php
 * @param string $access   Who is allowed to execute this action: public, logged_in, admin.
 *                         (default: logged_in)
 *
 * @return bool
 */
function elgg_register_action($action, $filename = "", $access = 'logged_in') {
	return _elgg_services()->actions->register($action, $filename, $access);
}

/**
 * Unregisters an action
 *
 * @param string $action Action name
 * @return bool
 * @since 1.8.1
 */
function elgg_unregister_action($action) {
	return _elgg_services()->actions->unregister($action);
}

/**
 * Get an HMAC token builder/validator object
 *
 * @param mixed $data HMAC data string or serializable data
 * @return \Elgg\Security\Hmac
 * @since 1.11
 */
function elgg_build_hmac($data) {
	return _elgg_services()->hmac->getHmac($data);
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
 *
 * @return string|false
 */
function generate_action_token($timestamp) {
	return elgg()->csrf->generateActionToken($timestamp);
}

/**
 * Regenerate a new site key (32 bytes: "z" to indicate format + 186-bit key in Base64 URL).
 *
 * @return mixed The site secret hash
 * @access private
 */
function init_site_secret() {
	$secret = SiteSecret::regenerate(_elgg_services()->crypto, _elgg_services()->configTable);
	_elgg_services()->setValue('siteSecret', $secret);
	return $secret->get();
}

/**
 * Get the strength of the site secret
 *
 * @return string "strong", "moderate", or "weak"
 * @access private
 */
function _elgg_get_site_secret_strength() {
	return _elgg_services()->siteSecret->getStrength();
}

/**
 * Check if an action is registered and its script exists.
 *
 * @param string $action Action name
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_action_exists($action) {
	return _elgg_services()->actions->exists($action);
}

/**
 * Checks whether the request was requested via ajax
 *
 * @return bool whether page was requested via ajax
 * @since 1.8.0
 */
function elgg_is_xhr() {
	return _elgg_services()->request->isXmlHttpRequest();
}
