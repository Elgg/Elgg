<?php
/**
 * Elgg Actions
 */

/**
 * Registers an action.
 *
 * Actions are registered to a script in the system and are executed
 * by the URL http://elggsite.org/action/action_name/.
 *
 * $filename must be the full path of the file to register or a path relative
 * to the core actions/ dir.
 *
 * Actions should be namespaced for your plugin.  Example:
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
 * @param array  $params   Additional params for the action route registration:
 *                         - middleware: additional middleware on the action route
 *
 * @return void
 */
function elgg_register_action(string $action, string $filename = '', string $access = 'logged_in', array $params = []): void {
	_elgg_services()->actions->register($action, $filename, $access, $params);
}

/**
 * Unregisters an action
 *
 * @param string $action Action name
 * @return void
 * @since 1.8.1
 */
function elgg_unregister_action(string $action): void {
	_elgg_services()->actions->unregister($action);
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
