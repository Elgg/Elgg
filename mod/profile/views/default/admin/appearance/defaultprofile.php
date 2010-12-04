<?php
/**
 * Elgg profile - Admin area: edit default profile fields
 *
 * @package ElggProfile
 */

$title = elgg_view_title(elgg_echo('profile:edit:default'));
$form = elgg_view('profile/editdefaultprofile');

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
							'body' => elgg_view('input/submit', array('value' => elgg_echo('profile:resetdefault'), 'class' => 'action-button disabled')),
							'action' => 'action/profile/editdefault/reset'
						)
					);
					
$body = <<<__HTML
$title
$form
$listing
<div class="default_profile_reset">
	$resetlisting
</div>
__HTML;

echo $body;
