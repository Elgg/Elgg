<?php
/**
 * Aggregate action for saving settings
 *
 * To see the individual action methods, enable the developers plugin, visit Admin > Inspect > Plugin Hooks
 * and search for "usersettings:save". The default methods are listed below:
 *
 * @see _elgg_set_user_language
 * @see _elgg_set_user_password
 * @see _elgg_set_user_default_access
 * @see _elgg_set_user_name
 * @see _elgg_set_user_username
 * @see _elgg_set_user_email
 */

elgg_make_sticky_form('usersettings');

// callbacks should return false to indicate that the sticky form should not be cleared
if (elgg_trigger_plugin_hook('usersettings:save', 'user', null, true)) {
	elgg_clear_sticky_form('usersettings');
}

return elgg_ok_response();
