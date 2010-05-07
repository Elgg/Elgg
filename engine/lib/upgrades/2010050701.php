<?php
/**
 * Removes the Walled Garden plugin in favor of new system settings
 */

$access = elgg_set_ignore_access(TRUE);

if (is_plugin_enabled('walledgarden')) {
	disable_plugin('walledgarden');
	set_config('allow_registration', FALSE);
} else {
	set_config('allow_registration', TRUE);
}

elgg_set_ignore_access($access);
