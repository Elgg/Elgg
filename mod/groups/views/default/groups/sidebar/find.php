<?php
/**
 * Group search
 *
 * @package ElggGroups
 */

$body = elgg_view_form('groups/find', [
	'action' => 'groups/search',
	'method' => 'get',
	'disable_security' => true,
]);

echo elgg_view_module('aside', elgg_echo('groups:search'), $body);
