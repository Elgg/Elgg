<?php

/**
 * Form body for editing a friend collection
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['collection'] Friend collection
 */
$collection = elgg_extract('collection', $vars);
if (!$collection || !$collection->id || !can_edit_access_collection($collection->id)) {
	return;
}

$members = get_members_of_access_collection($collection->id);

echo '<div>';
echo '<label>' . elgg_echo('friends:addfriends') . '</label>';
echo elgg_view('input/friendspicker', array(
	'value' => $members
));
echo '</div>';

echo '<div class="elgg-foot clearfix">';
echo elgg_view('input/hidden', array(
	'name' => 'collection_id',
	'value' => $collection->id,
));
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));
echo '</div>';
