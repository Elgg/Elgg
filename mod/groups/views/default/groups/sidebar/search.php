<?php
/**
 * Search for content in this group
 */

if (!elgg_is_active_plugin('search')) {
	return;
}

$body = elgg_view_form('groups/search', [
	'action' => elgg_generate_url('default:search'),
	'method' => 'get',
	'disable_security' => true,
	'role' => 'search',
	'aria-label' => elgg_echo('groups:aria:label:search_in_group'),
], $vars);

echo elgg_view_module('aside', elgg_echo('groups:search_in_group'), $body);
