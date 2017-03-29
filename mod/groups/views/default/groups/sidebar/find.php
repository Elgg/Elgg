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
	'class' => 'card-block',
]);

echo elgg_view_module('aside', elgg_echo('groups:searchtag'), $body, [
	'class' => 'card',
]);
