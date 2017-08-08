<?php
/**
 * Aggregate action for saving settings
 */

elgg_make_sticky_form('usersettings');

// callbacks should return false to indicate that the sticky form should not be cleared
if (elgg_trigger_plugin_hook('usersettings:save', 'user', null, true)) {
	elgg_clear_sticky_form('usersettings');
}

return elgg_ok_response();
