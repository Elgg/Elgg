<?php
/**
 * Group search
 */

$body = elgg_view_form('groups/find', [
	'action' => elgg_generate_url('collection:group:group:search'),
	'method' => 'get',
	'disable_security' => true,
	'role' => 'search',
	'aria-label' => elgg_echo('groups:aria:label:group_search'),
]);

echo elgg_view_module('aside', elgg_echo('groups:search'), $body);
