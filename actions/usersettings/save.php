<?php
/**
 * Aggregate action for saving settings
 *
 * @package Elgg
 * @subpackage Core
 */

global $CONFIG;

gatekeeper();

trigger_plugin_hook('usersettings:save','user');

forward($_SERVER['HTTP_REFERER']);
