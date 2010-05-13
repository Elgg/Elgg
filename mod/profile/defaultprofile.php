<?php
/**
 * Elgg profile - Admin area: edit default profile fields
 *
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

admin_gatekeeper();
set_context('admin');

$title = elgg_view_title(elgg_echo('profile:edit:default'));
$form = elgg_view('profile/editdefaultprofile');

set_context('search');

// List form elements
$n = 0;
$loaded_defaults = array();
$items = array();
if ($fieldlist = get_plugin_setting('user_defined_fields', 'profile')) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach($fieldlistarray as $listitem) {
		if ($translation = get_plugin_setting("admin_defined_profile_{$listitem}", 'profile')) {
			$item = new stdClass;
			$item->translation = $translation;
			$item->shortname = $listitem;
			$item->name = "admin_defined_profile_{$listitem}";
			$item->type = get_plugin_setting("admin_defined_profile_type_{$listitem}", 'profile');
			$items[] = $item;
		}
	}
}

$listing = elgg_view('profile/editdefaultprofileitems',array('items' => $items, 'fieldlist' => $fieldlist));

$resetlisting = elgg_view('input/form',
						array (
							'body' => elgg_view('input/submit', array('value' => elgg_echo('profile:resetdefault'), 'class' => 'action_button disabled')),
							'action' => $CONFIG->wwwroot . 'action/profile/editdefault/reset'
						)
					);
					
$resetlisting = "<div class='default_profile_reset'>{$resetlisting}</div>";

set_context('admin');

$body = elgg_view_layout("administration", $title . $form . $listing . $resetlisting);

page_draw(elgg_echo('profile:edit:default'), $body);