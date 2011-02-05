<?php
/**
 * Group members sidebar
 *
 * @package ElggGroups
 */

$all_link = elgg_view('output/url', array(
	'href' => 'pg/groups/members/' . $vars['entity']->guid,
	'text' => elgg_echo('groups:members:more'),
));

$body = '<div class="clearfix">';
$members = $vars['entity']->getMembers(10);
foreach ($members as $mem) {
	$body .= "<div class='member_icon'><a href=\"" . $mem->getURL() . "\">" . elgg_view("profile/icon", array('entity' => $mem, 'size' => 'tiny', 'override' => 'true')) . "</a></div>";
}
$body .= '</div>';
$body .= "<div class='center mts'>$all_link</div>";

echo elgg_view('layout/objects/module', array(
	'title' => elgg_echo("groups:members"),
	'body' => $body,
	'class' => 'elgg-module-aside',
));
