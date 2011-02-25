<?php
/**
 * Elgg friend collections add/edit
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] Optionally, the collection edit
 */

// var_export($vars['collection'][0]->id);

// Set title, form destination
if (isset($vars['collection'])) {
	$action = "friends/editcollection";
	$title = $vars['collection'][0]->name;
	$highlight = 'default';
} else  {
	$action = "friends/addcollection";
	$title = "";
	$highlight = 'all';
}


$form_body = "<div class='contentWrapper'><div><label>" . elgg_echo("friends:collectionname") . "<br />" .
	elgg_view("input/text", array(
		"name" => "collection_name",
		"value" => $title,
	)) . "</label></div>";

$form_body .= "<div>";

if($vars['collection_members']){
	$form_body .= elgg_echo("friends:collectionfriends") . "<br />";
	foreach($vars['collection_members'] as $mem){
		$form_body .= elgg_view_entity_icon($mem, 'tiny');
		$form_body .= $mem->name;
	}
}

$form_body .= "</div>";

$form_body .= "<div><label>" . elgg_echo("friends:addfriends") . "</label>".
			elgg_view('core/friends/picker',array('entities' => $vars['friends'], 'name' => 'friends_collection', 'highlight' => $highlight)) . "</div>";

$form_body .= "<div>";
if (isset($vars['collection'])) {
	$form_body .= elgg_view('input/hidden', array('name' => 'collection_id', 'value' => "{$vars['collection'][0]->id}"));
}
$form_body .= elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('save')));
$form_body .= "</div></div>";

echo $form_body;