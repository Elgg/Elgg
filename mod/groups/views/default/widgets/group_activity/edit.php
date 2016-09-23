<?php
/**
 * Group activity widget settings
 */

// once autocomplete is working use that
$groups = elgg_get_logged_in_user_entity()->getGroups(array('limit' => 0));
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
$group_dropdown = elgg_view('input/select', $params);
?>
<div>
	<?php echo elgg_echo('groups:widget:group_activity:edit:select'); ?>:
	<?php echo $group_dropdown; ?>
</div>
<?php

$widget = elgg_extract('entity', $vars);
// set default value
if (!isset($widget->num_display)) {
	$widget->num_display = 8;
}

echo elgg_view('object/widget/edit/num_display', [
	'entity' => $widget,
]);

$title_input = elgg_view('input/hidden', array('name' => 'title'));
echo $title_input;
