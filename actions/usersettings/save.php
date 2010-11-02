<?php
/**
 * Aggregate action for saving settings
 *
 * @package Elgg.Core
 * @subpackage UserSettings
 */

global $CONFIG;

gatekeeper();

trigger_plugin_hook('usersettings:save', 'user');

forward(REFERER);
