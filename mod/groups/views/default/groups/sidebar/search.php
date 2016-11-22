<?php
/**
 * Search for content in this group
 */

$body = elgg_view_form('groups/search', array(
	'action' => 'search',
	'method' => 'get',
	'disable_security' => true,
), $vars);

echo elgg_view_module('aside', elgg_echo('groups:search_in_group'), $body);
