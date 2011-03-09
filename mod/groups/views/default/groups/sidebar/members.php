<?php
/**
 * Group members sidebar
 *
 * @todo clean up html
 *
 * @package ElggGroups
 */

$all_link = elgg_view('output/url', array(
	'href' => 'groups/members/' . $vars['entity']->guid,
	'text' => elgg_echo('groups:members:more'),
));

$body = '<div class="clearfix">';
$members = $vars['entity']->getMembers(10);
foreach ($members as $mem) {
	$body .= "<div class='member_icon'><a href=\"{$mem->getURL()}\">" . elgg_view_entity_icon($mem, 'tiny', array('override' => 'true')) . "</a></div>";
}
$body .= '</div>';
$body .= "<div class='center mts'>$all_link</div>";

echo elgg_view_module('aside', elgg_echo('groups:members'), $body);