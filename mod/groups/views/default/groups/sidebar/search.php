<?php
/**
 * Search for content in this group
 */

$body = elgg_view_form('groups/search', [
	'action' => 'search',
	'method' => 'get',
	'disable_security' => true,
	'prevent_double_submit' => true,
], $vars);

echo elgg_view_module('aside', elgg_echo('groups:search_in_group'), $body);
