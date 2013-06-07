<?php
/**
 * Aggregate action for saving settings
 *
 * @package Elgg.Core
 * @subpackage UserSettings
 */

elgg_make_sticky_form('usersettings');

if (elgg_trigger_plugin_hook('usersettings:save', 'user', null, true)) {
	elgg_clear_sticky_form('usersettings');
}

forward(REFERER);
