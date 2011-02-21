<?php
/**
 * Removes the Walled Garden plugin in favor of new system settings
 */

$access = elgg_set_ignore_access(TRUE);

if (elgg_is_active_plugin('walledgarden')) {
	disable_plugin('walledgarden');
	set_config('allow_registration', FALSE);
	set_config('walled_garden', TRUE);
} else {
	set_config('allow_registration', TRUE);
	set_config('walled_garden', FALSE);
}

$disable_registration = elgg_get_config('disable_registration');
$allow_registration = !$disable_registration;
elgg_save_config('allow_registration', $allow_registration);

elgg_set_ignore_access($access);
