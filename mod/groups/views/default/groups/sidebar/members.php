<?php
/**
 * Group members sidebar
 *
 * @todo clean up html
 *
 * @package ElggGroups
 */

$all_link = elgg_view('output/url', array(
	'href' => 'pg/groups/members/' . $vars['entity']->guid,
	'text' => elgg_echo('groups:members:more'),
));

$body = '<ul class="elgg-menu-hz">';
$members = $vars['entity']->getMembers(10);
foreach ($members as $mem) {
	$body .= '<li class="pas">' . elgg_view_entity_icon($mem, 'tiny', array('override' => true)) . '</li>';
}
$body .= '</ul>';
$body .= "<div class='center mts'>$all_link</div>";

echo elgg_view_module('aside', elgg_echo('groups:members'), $body);