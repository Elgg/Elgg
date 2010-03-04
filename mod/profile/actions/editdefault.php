<?php
/**
 * Elgg profile plugin edit default profile action
 *
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

global $CONFIG;
admin_gatekeeper();

$label = sanitise_string(get_input('label'));
$type = sanitise_string(get_input('type'));

if (($label) && ($type)){
	// find next index for new field
	$n = 0;
	while (get_plugin_setting("admin_defined_profile_$n", 'profile')) {
		$n++;
	}

	if ( (set_plugin_setting("admin_defined_profile_$n", $label, 'profile')) && 
		(set_plugin_setting("admin_defined_profile_type_$n", $type, 'profile'))) {
		system_message(elgg_echo('profile:editdefault:success'));
	} else {
		register_error(elgg_echo('profile:editdefault:fail'));
	}
} else {
	register_error(elgg_echo('profile:editdefault:fail'));
}

forward($_SERVER['HTTP_REFERER']);