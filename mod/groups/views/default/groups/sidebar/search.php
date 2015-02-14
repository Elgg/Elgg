<?php
/**
 * Search for content in this group
 *
 * @uses vars['entity'] ElggGroup
 */

if (!elgg_is_active_plugin('search')) {
	return;
}

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

$url = elgg_get_site_url() . 'search';
$body = elgg_view_form('groups/search', array(
	'action' => $url,
	'method' => 'get',
	'disable_security' => true,
), $vars);

echo elgg_view_module('aside', elgg_echo('groups:search_in_group'), $body);