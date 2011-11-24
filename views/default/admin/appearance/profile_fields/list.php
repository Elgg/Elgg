<?php
/**
 * Profile fields.
 * 
 * @todo Needs some review
 */

// List form elements
$n = 0;
$loaded_defaults = array();
$items = array();
$fieldlist = elgg_get_config('profile_custom_fields');
if ($fieldlist) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach ($fieldlistarray as $listitem) {
		$translation = elgg_get_config("admin_defined_profile_$listitem");
		$type = elgg_get_config("admin_defined_profile_type_$listitem");
		if ($translation && $type) {
			$item = new stdClass;
			$item->translation = $translation;
			$item->shortname = $listitem;
			$item->name = "admin_defined_profile_$listitem";
			$item->type = elgg_echo("profile:field:$type");
			$items[] = $item;
		}
	}
}
?>
<ul id="elgg-profile-fields" class="mvm">
<?php

$save = elgg_echo('save');
$cancel = elgg_echo('cancel');

foreach ($items as $item) {
	echo elgg_view("profile/", array('value' => $item->translation));

	//$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
	$url = elgg_view('output/url', array(
		'href' => "action/profile/fields/delete?id={$item->shortname}",
		'text' => elgg_view_icon('delete-alt'),
		'is_action' => true,
		'is_trusted' => true,
	));
	$type = elgg_echo($item->type);
	echo <<<HTML
<li id="$item->shortname" class="clearfix">
	<span class="elgg-icon elgg-icon-drag-arrow elgg-state-draggable"></span>
	<b><span id="elgg-profile-field-{$item->shortname}" class="elgg-state-editable">$item->translation</span></b> [$type] $url
</li>
HTML;
}

?>
</ul>