<?php
/**
 * Group status for logged in user
 *
 * @package ElggGroups
 *
 * @uses $vars['entity'] Group entity
 */

if (!elgg_is_logged_in()) {
	return true;
}

$body = elgg_view_menu('groups:my_status', [
	'class' => 'elgg-menu-page',
]);
echo elgg_view_module('aside', elgg_echo('groups:my_status'), $body);
