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
if ($fieldlist = elgg_get_config('profile_custom_fields')) {
	$fieldlistarray = explode(',', $fieldlist);
	foreach ($fieldlistarray as $listitem) {
		if ($translation = elgg_get_config("admin_defined_profile_{$listitem}")) {
			$item = new stdClass;
			$item->translation = $translation;
			$item->shortname = $listitem;
			$item->name = "admin_defined_profile_{$listitem}";
			$item->type = elgg_get_config("admin_defined_profile_type_{$listitem}");
			$items[] = $item;
		}
	}
}
?>
<div id="list">
	<ul id="sortable_profile_fields">
<?php

$save = elgg_echo('save');
$cancel = elgg_echo('cancel');

foreach ($items as $item) {
	echo elgg_view("profile/", array('value' => $item->translation));

	//$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
	$url = elgg_view('output/url', array(
		'href' => "action/profile/fields/delete?id={$item->shortname}",
		'is_action' => TRUE,
		'text' => elgg_view_icon('delete-alt'),
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
</div>
<div id="tempList"></div>

<input name="sortableListOrder" type="hidden" id="sortableListOrder" value="<?php echo $fieldlist; ?>" />