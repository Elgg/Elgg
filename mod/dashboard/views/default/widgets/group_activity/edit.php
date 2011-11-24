<?php
/**
 * Group activity widget settings
 */

// once autocomplete is working use that
$groups = elgg_get_logged_in_user_entity()->getGroups("", 0);
$mygroups = array();
if (!$vars['entity']->group_guid) {
	$mygroups[0] = '';
}
foreach ($groups as $group) {
	$mygroups[$group->guid] = $group->name;
}
$params = array(
	'name' => 'params[group_guid]',
	'value' => $vars['entity']->group_guid,
	'options_values' => $mygroups,
);
$group_dropdown = elgg_view('input/dropdown', $params);
?>
<div>
	<?php echo elgg_echo('dashboard:widget:group:select'); ?>:
	<?php echo $group_dropdown; ?>
</div>
<?php

// set default value for number to display
if (!isset($vars['entity']->num_display)) {
	$vars['entity']->num_display = 8;
}

$params = array(
	'name' => 'params[num_display]',
	'value' => $vars['entity']->num_display,
	'options' => array(5, 8, 10, 12, 15, 20),
);
$num_dropdown = elgg_view('input/dropdown', $params);

?>
<div>
	<?php echo elgg_echo('widget:numbertodisplay'); ?>:
	<?php echo $num_dropdown; ?>
</div>

