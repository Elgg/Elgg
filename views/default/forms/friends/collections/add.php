<?php
/**
 * Form body for editing or adding a friend collection
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['collection'] Optionally, the collection to edit
 */

// Set title, form destination
if (isset($vars['collection'])) {
	$title = $vars['collection']->name;
	$highlight = 'default';
} else  {
	$title = "";
	$highlight = 'all';
}

echo "<div class=\"mtm\"><label>" . elgg_echo("friends:collectionname") . "<br/>";
echo elgg_view("input/text", array(
		"name" => "collection_name",
		"value" => $title,
	));
echo "</label></div>";

echo "<div>";
if ($vars['collection_members']) {
	echo elgg_echo("friends:collectionfriends") . "<br />";
	foreach ($vars['collection_members'] as $mem) {
		echo elgg_view_entity_icon($mem, 'tiny');
		echo $mem->name;
	}
}
echo "</div>";

echo "<div><label>" . elgg_echo("friends:addfriends") . "</label>";
echo elgg_view('input/friendspicker', array(
	'entities' => $vars['friends'],
	'name' => 'friends_collection',
	'highlight' => $highlight,
));
echo "</div>";

echo "<div>";
if (isset($vars['collection'])) {
	echo elgg_view('input/hidden', array(
		'name' => 'collection_id',
		'value' => $vars['collection']->id,
	));
}
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));
echo "</div>";
