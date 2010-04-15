<?php
/**
 * Saves granular access
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$perms = get_input('perms', array());

if (set_plugin_setting('ecml_permissions', serialize($perms), 'ecml')) {
	system_message(elgg_echo('ecml:admin:permissions_saved'));
} else {
	register_error(elgg_echo('ecml:admin:cannot_save_permissions'));
}

forward($_SERVER['HTTP_REFERER']);
