<?php
/**
 * Group members sidebar
 *
 * @package ElggGroups
 */

$body = '';
$members = $vars['entity']->getMembers(10);
foreach ($members as $mem) {
	$body .= "<div class='member_icon'><a href=\"" . $mem->getURL() . "\">" . elgg_view("profile/icon", array('entity' => $mem, 'size' => 'tiny', 'override' => 'true')) . "</a></div>";
}

echo elgg_view('layout/objects/module', array(
	'title' => elgg_echo("groups:members"),
	'body' => $body,
	'class' => 'elgg-aside-module',
));
