<?php
/**
 * Aggregate action for saving settings
 *
 * @package Elgg
 * @subpackage Core
 * @link http://elgg.org/
 */

global $CONFIG;

gatekeeper();

trigger_plugin_hook('usersettings:save','user');

forward($_SERVER['HTTP_REFERER']);
