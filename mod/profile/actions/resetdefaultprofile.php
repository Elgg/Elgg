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

if ($fieldlist = get_plugin_setting('user_defined_fields', 'profile')) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach($fieldlistarray as $listitem) {
		set_plugin_setting("admin_defined_profile_{$listitem}", '', 'profile');
		set_plugin_setting("admin_defined_profile_type_{$listitem}", '', 'profile');
	}
}

set_plugin_setting('user_defined_fields', FALSE, 'profile');

system_message(elgg_echo('profile:defaultprofile:reset'));

forward($_SERVER['HTTP_REFERER']);