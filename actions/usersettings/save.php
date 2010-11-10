<?php
/**
 * Aggregate action for saving settings
 *
 * @package Elgg.Core
 * @subpackage UserSettings
 */

global $CONFIG;

gatekeeper();

elgg_trigger_plugin_hook('usersettings:save', 'user');

forward(REFERER);
