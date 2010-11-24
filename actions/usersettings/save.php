<?php
/**
 * Aggregate action for saving settings
 *
 * @package Elgg.Core
 * @subpackage UserSettings
 */

global $CONFIG;

elgg_trigger_plugin_hook('usersettings:save', 'user');

forward(REFERER);
