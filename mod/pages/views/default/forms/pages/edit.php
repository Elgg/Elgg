<?php
/**
 * Page edit form body
 *
 * @package ElggPages
 */

$user = elgg_get_logged_in_user_entity();
$entity = elgg_extract('entity', $vars);
$can_change_access = true;
if ($user && $entity) {
	$can_change_access = ($user->isAdmin() || $user->getGUID() == $entity->owner_guid);
}

echo '<div>';
echo '<label>' . elgg_echo('pages:title') . '</label><br />';
echo elgg_view('input/text', array(
	'name' => 'title',
	'value' => elgg_extract('title', $vars),
));
echo '</div>';

echo '<div>';
echo '<label>' . elgg_echo('pages:description') . '</label>';
echo elgg_view('input/longtext', array(
	'name' => 'description',
	'value' => elgg_extract('description', $vars)
));
echo '</div>';

echo '<div>';
echo '<label>' . elgg_echo('tags') . '</label><br />';
echo elgg_view('input/tags', array(
	'name' => 'tags',
	'value' => elgg_extract('tags', $vars)
));
echo '</div>';

if (!$vars['parent_guid'] && !$vars['guid']) {
	echo '<div>';
	echo '<label>' . elgg_echo('pages:parent_guid') . '</label><br />';
	echo elgg_view('pages/input/parent', array(
		'name' => 'parent_guid',
		'value' => elgg_extract('parent_guid', $vars),
		'entity' => $entity
	));
	echo '</div>';
}

echo '<div>';
echo '<label>' . elgg_echo('pages:access_id') . '</label><br />';
echo elgg_view('input/access', array(
	'name' => 'access_id',
	'value' => elgg_extract('access_id', $vars)
));
echo '</div>';

echo '<div>';
echo '<label>' . elgg_echo('pages:write_access_id') . '</label><br />';
echo elgg_view('input/write_access', array(
	'name' => 'write_access_id',
	'value' => elgg_extract('write_access_id', $vars)
));
echo '</div>';

// deprecated way of form extension
echo elgg_view("pages/input/deprecated", $vars);

$cats = elgg_view('input/categories', $vars);
if (!empty($cats)) {
	echo $cats;
}


echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'page_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if (!$vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';
