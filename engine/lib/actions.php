<?php
/**
 * Elgg Actions
 */

use Elgg\Database\SiteSecret;

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
function elgg_register_action(string $action, $filename = '', string $access = 'logged_in'): bool {
	return _elgg_services()->actions->register($action, $filename, $access);
}

/**
 * Unregisters an action
 *
 * @param string $action Action name
 * @return bool
 * @since 1.8.1
 */
function elgg_unregister_action(string $action): bool {
	return _elgg_services()->actions->unregister($action);
}

/**
 * Get an HMAC token builder/validator object
 *
 * @param mixed $data HMAC data string or serializable data
 * @return \Elgg\Security\Hmac
 * @since 1.11
 */
function elgg_build_hmac($data): \Elgg\Security\Hmac {
	return _elgg_services()->hmac->getHmac($data);
}

/**
 * Regenerate a new site key (32 bytes: "z" to indicate format + 186-bit key in Base64 URL).
 *
 * @return mixed The site secret hash
 * @internal
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
 * @internal
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
function elgg_action_exists(string $action): bool {
	return _elgg_services()->actions->exists($action);
}

/**
 * Checks whether the request was requested via ajax
 *
 * @return bool whether page was requested via ajax
 * @since 1.8.0
 */
function elgg_is_xhr(): bool {
	return _elgg_services()->request->isXmlHttpRequest();
}
