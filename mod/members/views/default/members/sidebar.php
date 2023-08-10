<?php
/**
 * Members sidebar
 */

$body = elgg_view_form('members/search', [
	'method' => 'get',
	'action' => elgg_generate_url('search:user:user'),
	'disable_security' => true,
	'role' => 'search',
	'aria-label' => elgg_echo('members:aria:label:member_search'),
]);

echo elgg_view_module('aside', elgg_echo('members:search'), $body);
